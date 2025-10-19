/**************************************************************************/
/*  app.js                                                                */
/**************************************************************************/
/*                        This file is part of:                           */
/*                              daamlite                                  */
/*                 https://github.com/armindeck/daamlite                  */
/**************************************************************************/
/* Copyright (c) 2025 Armin Deck                                          */
/*                                                                        */
/* Permission is hereby granted, free of charge, to any person obtaining  */
/* a copy of this software and associated documentation files (the        */
/* "Software"), to deal in the Software without restriction, including    */
/* without limitation the rights to use, copy, modify, merge, publish,    */
/* distribute, sublicense, and/or sell copies of the Software, and to     */
/* permit persons to whom the Software is furnished to do so, subject to  */
/* the following conditions:                                              */
/*                                                                        */
/* The above copyright notice and this permission notice shall be         */
/* included in all copies or substantial portions of the Software.        */
/*                                                                        */
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,        */
/* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF     */
/* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. */
/* IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY   */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,   */
/* TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE      */
/* SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.                 */
/**************************************************************************/


const menuCheckbox = document.getElementById("show-menu");
const menuNav = document.querySelector(".header__nav");
const menuButton = document.querySelector(".header__menu-button");

function toggleTheme() {
  const body = document.body;
  const btn = document.getElementById('toggle-theme');
  const icon = btn.querySelector('span');
  const theme = body.getAttribute('data-theme');
  if (theme === 'dark') {
    body.setAttribute('data-theme', 'light');
    icon.className = 'fas fa-moon';
    localStorage.setItem('theme', 'light');
    sendThemeToServer('light');
  } else {
    body.setAttribute('data-theme', 'dark');
    icon.className = 'fas fa-sun';
    localStorage.setItem('theme', 'dark');
    sendThemeToServer('dark');
  }
}

async function sendThemeToServer(theme) {
  try {
    const res = await fetch('http://localhost/daamlite/process/change-theme.php', {
      method: 'POST',
      headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
      body: JSON.stringify({ theme })
    });
    if (!res.ok) {
      console.warn('No se pudo enviar el tema al servidor:', res.status);
    }
    // opcional: manejar respuesta JSON si el PHP devuelve algo
    // const data = await res.json();
  } catch (err) {
    console.error('Error enviando tema al servidor:', err);
  }
}

window.addEventListener('DOMContentLoaded', function() {
  // Aplica el tema guardado al cargar
  const saved = localStorage.getItem('theme') || 'light';
  document.body.setAttribute('data-theme', saved);

  // Crear botón de cambio de tema si no existe
  const nav = document.querySelector('.header__menu');
  let btn = document.getElementById('toggle-theme');
  if (nav && !btn) {
    btn = document.createElement('button');
    btn.className = 'header__button';
    btn.id = 'toggle-theme';
    btn.type = 'button';
    btn.title = 'Cambiar tema';
    btn.innerHTML = '<span class="fas fa-moon"></span>';
    btn.onclick = toggleTheme;
    nav.appendChild(btn);
  }

  // Ajustar icono según tema guardado
  if (btn) {
    const icon = btn.querySelector('span');
    icon.className = saved === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
  }

  document.addEventListener("click", function(event) {
        const isClickInsideMenu = menuCheckbox.contains(event.target);
        
        if(isClickInsideMenu){
            if (menuCheckbox.checked) {
                menuNav.classList.add("header__nav--visible");
                menuButton.classList.add("header__menu-button--active");
            } else {
                menuNav.classList.remove("header__nav--visible");
                menuButton.classList.remove("header__menu-button--active");
            }
        }
    });

    initPjax();
});

async function loadPage(url, push = true) {
    try {
        const res = await fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });
        if (!res.ok) {
            // si hay error, navegar normalmente
            window.location.href = url;
            return;
        }
        const html = await res.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newMain = doc.querySelector('main#main') || doc.querySelector('main');
        if (!newMain) {
            // si no viene main, recargar la página completa
            window.location.href = url;
            return;
        }

        // Reemplazar contenido de main
        const currentMain = document.querySelector('main#main') || document.querySelector('main');
        currentMain.replaceWith(newMain);

        // Actualizar título
        if (doc.title) document.title = doc.title;

        // Actualizar metatags básicos (opcional, se pueden añadir más)
        const newDescription = doc.querySelector('meta[name="description"]');
        if (newDescription) {
            let meta = document.querySelector('meta[name="description"]');
            if (!meta) {
                meta = document.createElement('meta');
                meta.name = 'description';
                document.head.appendChild(meta);
            }
            meta.content = newDescription.content;
        }

        // Ejecutar scripts inline que vinieran dentro del nuevo main (si hay)
        newMain.querySelectorAll('script').forEach(oldScript => {
            const s = document.createElement('script');
            if (oldScript.src) {
                s.src = oldScript.src;
                s.async = false;
            } else {
                s.textContent = oldScript.textContent;
            }
            document.body.appendChild(s);
            s.remove();
        });

        // Actualizar el URL en el historial
        if (push) history.pushState({ url }, '', url);

        // Opcional: volver a inicializar comportamiento de la página (menú, botones, etc.)
        // Si tus listeners están ligados a elementos que fueron reemplazados en main, re-inicialízalos aquí.
    } catch (err) {
        console.error('Error cargando página:', err);
        window.location.href = url;
    }
}

function isInternalLink(link) {
    try {
        const url = new URL(link, location.href);
        return url.origin === location.origin;
    } catch {
        return false;
    }
}

function initPjax() {
    // Interceptar clicks en enlaces
    document.addEventListener('click', function (e) {
        // Buscar enlace más cercano
        const a = e.target.closest('a');
        if (!a) return;
        if (a.target && a.target !== '_self') return; // respetar target
        if (a.hasAttribute('download')) return;
        if (a.classList.contains('no-ajax')) return; // clase para ignorar
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#')) return;
        if (!isInternalLink(href)) return;

        e.preventDefault();
        const url = new URL(href, location.href).href;
        if (url === location.href) return;
        loadPage(url, true);
    });

    // Manejar back/forward
    window.addEventListener('popstate', function (e) {
        const url = (e.state && e.state.url) ? e.state.url : location.href;
        loadPage(url, false);
    });
}