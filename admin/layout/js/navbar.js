// select the actual navbar
let navbarItems = document.querySelectorAll(".nav-btn");
let href = location.href;

let myNavbarArray = [];
navbarItems.forEach((navbarItem) => {
  let navbarItemData = navbarItem.getAttribute("data-name");
  myNavbarArray.push(navbarItemData);
});

myNavbarArray.forEach((e) => {
  if (href.includes(e)) {
    navbarItems.forEach((ele) => {
      if (ele.getAttribute("data-name") == e) {
        ele.classList.add("active");
      }
    });
  } else {
    console.log("no");
  }
});

//control of the navbar hide / show
let barIcon = document.querySelector(".header-sec .icon i");
let navbar = document.querySelector(".navbar-container");
barIcon.onclick = () => {
  if (navbar.classList.contains("active")) {
    navbar.classList.remove("active");
  } else {
    navbar.classList.add("active");
  }
};
