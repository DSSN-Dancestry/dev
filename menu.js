function openNav() {
    document.getElementById("side-navbar").style.width = "300px"; /* Open the side-navbar */
    document.getElementById("opacity-cover").style.height = "100%"; /* Turn on the opacity cover */
    document.body.style.overflow = "hidden"; /* Prevent scrolling */
  }
  
function closeNav() {
    document.getElementById("side-navbar").style.width = "0";
    document.getElementById("opacity-cover").style.height = "0%";
    document.body.style.overflow = "auto";
}