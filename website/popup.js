function toggleModal() {
  document.getElementById("modal").classList.toggle("show-modal");
}

function windowOnClick(event) {
 if (event.target ===  document.getElementById("modal")) {
     toggleModal();
 }
}

window.addEventListener("click", windowOnClick);
