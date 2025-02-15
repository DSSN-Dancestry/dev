//alert(platform.name + " " + platform.version);

//alert(version_str.split("."));
function error_and_redirect_to_root() {
    //alert("Your browser is too old for this page to work.");
    window.location.href = './browser_error.php';

}
function strict_check() {
    version_str = platform.version;
    if (platform.name === "Chrome" && version_str.split(".")[0] < 80) {
        error_and_redirect_to_root();
    }
    if (platform.name === "IE") {
        error_and_redirect_to_root();
    }
    if (platform.name === "Safari" && version_str.split(".")[0] < 10) {
        error_and_redirect_to_root();
    }
    //Non-Chromium Edge is already out of support
    if (platform.name === "Microsoft Edge" && version_str.split(".")[0] < 80) {
        error_and_redirect_to_root();
    }
    //Why would anyone use this browser?
    if (platform.description.includes("Konqueror") || platform.description.includes("PlayStation")) {
        alert("You cannot use \"" + platform.description + "\" in CL.");
        error_and_redirect_to_root();
    }
    if (platform.name === "Opera" && version_str.split(".")[0] < 40) {
        error_and_redirect_to_root();
    }
    if (platform.name === "Firefox" && version_str.split(".")[0] < 55) {
        error_and_redirect_to_root();
    }
}


// function mobile_warning() {
//     if (platform.os.family.includes("iOS") || platform.os.family.includes("Android")) {
//         alert("This page is not optimised for mobile experience.");
//     }
// }