 const selected = document.querySelector(".selected");
const optionsContainer = document.querySelector(".options-container");

const optionsList = document.querySelectorAll(".option");

selected.addEventListener("click", () => {
  optionsContainer.classList.toggle("active");
});

optionsList.forEach(o => {
  o.addEventListener("click", () => {
    selected.innerHTML = o.querySelector("label").innerHTML;
    optionsContainer.classList.remove("active");
  });
});


//------------------------------------------------------------------------------
 

 
const textarea =document.querySelector("textarea");
textarea.addEventListener("keyup", e =>{
let scHeight = e.target.scrollHeight; 
textarea.style.height = `${scHeight}px`;
});  