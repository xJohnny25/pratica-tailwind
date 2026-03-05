window.onload = function () {
  fetch("nav.html")
    .then((res) => res.text())
    .then((html) => {
      document.body.insertAdjacentHTML("afterbegin", html);

      const links = document.querySelectorAll(".nav-link");
      const currentFile = window.location.pathname.split("/").pop();

      links.forEach((a) => {
        const href = a.getAttribute("href");
        if (href === currentFile) {
          a.classList.add("active");
        }
      });
    });
};
