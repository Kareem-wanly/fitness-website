// show hide navbar menus
let bars = document.querySelector(".nav-list .bar");
let navbar = document.querySelector(".navbar-list");
bars.onclick = () => {
  navbar.classList.toggle("active");
}

// set active to the current page in navbar 
const href = location.href;
let dataArray = [];
let navbarItems = document.querySelectorAll(".navbar-list .list-item a");
navbarItems.forEach((e) => {
  let data = e.getAttribute("data-name");
  if(href.includes(data)) {
    e.classList.add("active");
  } else {
    console.log("no");
  }
})