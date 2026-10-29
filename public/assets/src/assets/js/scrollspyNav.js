function getContainerMargin() {
  var p = document.querySelector(".container-xxl");

  if (!p) {
    // Gracefully do nothing if the layout doesn't apply
    return;
  }

  var style = window.getComputedStyle(p);
  var sidenav = document.querySelector(".sidenav");

  if (sidenav) {
    sidenav.style.right = style.marginRight;
    sidenav.style.display = "block";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // Slight delay for layout rendering
  setTimeout(getContainerMargin, 100);
  window.addEventListener("resize", getContainerMargin);
});
