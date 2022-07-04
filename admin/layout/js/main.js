let panelBtns = document.querySelectorAll(".panel h3");
let panels = document.querySelectorAll(".panel-content");

panelBtns.forEach((panelBtn, index) => {
  panelBtn.onclick = () => {
    panels.forEach((panel, index2) => {
      if (index == index2) {
        if (panel.classList.contains("active")) {
          panel.classList.remove("active");
        } else {
          panel.classList.add("active");
        }
      }
    });
  };
});
