var stage = undefined;
var timeoutFunc;
// 3 months
var cookies_exp_hours = 2190;

/**
 * This is a driver function for the splash screen.
 * If cookie value of first_time_completed is not true, then a splash screen will show up.
 * @author Hanzhang Bai
 */


/**
 * This function shows up a welcome screen which is different from the splash screen.
 * If user skipped or completed all parts of tutorial, then the first block of code will
 * be executed.
 * Currently, there are 3 chapters and the if else is implemented BACKWARDS.
 * If you want to add more, then add one more cookie entry.
 * searchTutorial(), networkTutorial(), filterTutorial are 3 big tutorial chapters in this file.
 * @author Hanzhang Bai
 */
function tutorialWelcome() {
    stage = new Stage();
    stage.initStage();
    var tutorshow = document.getElementById("TutorShowUp");
    tutorshow.style.display = "block";
    tutorshow.style.zIndex = "110";
    //Check cookies in desending order because the progress will mark based on where it left off.    
    if (getCookie("skipped_tutorial_all") === "true") {
        document.getElementById("TutorWindowIfSkipped").style.display = "block";

        stage.highLightById("TutorWindowIfSkipped");
        var getStartNetwork = document.getElementById("Tutor_start_network_if_skipped");
        getStartNetwork.onclick = function () {
            /*document.getElementById("TutorWindow").style.display = "none";
            document.getElementById("TutorWindowIfSkipped").style.display = "none";
            resetCookie();
            searchTutorial();*/
            Skip();
        }
        var chapReview = document.getElementById("chapter_review_tutor_window");
        chapReview.onclick = function () {
            document.getElementById("TutorWindowIfSkipped").style.display = "none";
            chapterSelectInit();
        }
        var closeButton = document.getElementById("close_skipped_window_tutorial");
        closeButton.onclick = function () {
            closeAllWindows();
        }
    } else if (getCookie("completed_second_chapter") === "true") {
        document.getElementById("CompleteSecondChapter").style.display = "block";
        stage.highLightById("CompleteSecondChapter");
        var continue_button = document.getElementById("continue_to_third_chapter");
        continue_button.onclick = function () {
            document.getElementById("CompleteSecondChapter").style.display = "none";
            stage.close();
            filterTutorial();
        }
        var start_over_button = document.getElementById("start_over_second_chapter");
        start_over_button.onclick = function () {
            document.getElementById("CompleteSecondChapter").style.display = "none";
            resetCookie();
            searchTutorial();
        }
        var skip_button = document.getElementById("skip_from_second_chapter");
        skip_button.onclick = function () {
            Skip();
        }
        var closeButton = document.getElementById("close_welcome_window_tutorial");
        closeButton.onclick = function () {
            closeAllWindows();
        }
    } else if (getCookie("completed_first_chapter") === "true") {
        document.getElementById("CompleteFirstChapter").style.display = "block";
        stage.highLightById("CompleteFirstChapter");
        var continue_button = document.getElementById("continue_to_second_chapter");
        continue_button.onclick = function () {
            document.getElementById("CompleteFirstChapter").style.display = "none";
            $('#clear').click();
            document.getElementById("searchbox").value = "Melanie Aceto";
            $('#search').click();
            networkTutorial();
        }
        var start_over_button = document.getElementById("start_over_first_chapter");
        start_over_button.onclick = function () {
            document.getElementById("CompleteFirstChapter").style.display = "none";
            resetCookie();
            searchTutorial();
        }
        var skip_button = document.getElementById("skip_from_first_chapter");
        skip_button.onclick = function () {
            Skip();
        }
        var closeButton = document.getElementById("close_first_chapter_window_tutorial");
        closeButton.onclick = function () {
            closeAllWindows();
        }
    } else {
        var firstshow = document.getElementById("TutorWindow").style.display = "block";
        stage.highLightById("TutorWindow");
        var getStartNetwork = document.getElementById("Tutor_start_network");
        getStartNetwork.onclick = function () {
            document.getElementById("TutorWindow").style.display = "none";
            resetCookie();
            searchTutorial();
        }
        var skipWelcome1 = document.getElementById("Skip_rel");
        skipWelcome1.onclick = function () {
            Skip();
        }
        var closeButton = document.getElementById("close_welcome_window_tutorial");
        closeButton.onclick = function () {
            closeAllWindows();
        }

    }
    clearSearchText();

}

/**
 * This will show a popup for selecting chapters.
 * It should only be called by tutorialWelcome()
 * @author Hanzhang Bai
 */
function chapterSelectInit() {
    document.getElementById("ChaptersSelect").style.display = "block";
    stage.highLightById("ChaptersSelect");
    var searchButton = document.getElementById("restart_first_chapter");
    searchButton.onclick = function () {
        document.getElementById("ChaptersSelect").style.display = "none";
        searchTutorial();
    }
    var graphButton = document.getElementById("restart_second_chapter");
    graphButton.onclick = function () {
        document.getElementById("ChaptersSelect").style.display = "none";

        $('#clear').click();
        document.getElementById("searchbox").value = "Melanie Aceto";
        $('#search').click();

        networkTutorial();
    }
    var filtersButton = document.getElementById("restart_third_chapter");
    filtersButton.onclick = function () {
        document.getElementById("ChaptersSelect").style.display = "none";
        stage.close();
        filterTutorial();
    }
    var closeButton = document.getElementById("close_chapter_select");
    closeButton.onclick = function () {
        closeAllWindows();
    }
}

/**
 * It is a debug function that resets all cookies on this app.
 *
 */
function resetCookie() {
    console.log("Reset all cookies");
    document.cookie.split(";").forEach(function (c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
}

/**
 * It gets a cookie entry from cname which is an identifier for cookie.
 * @param cname
 * The return value is a string.
 * @returns {string}
 */
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

/**
 *
 * @param cname
 * @param cvalue
 * Expiration hour is controlled by the global variable at the top of this page.
 * @param exp_hours
 */
function setCookie(cname, cvalue, exp_hours) {
    var d = new Date();
    d.setTime(d.getTime() + (exp_hours * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

/**
 * This function should be called by the tutorialWelcome() or chapterSelectInit()
 */
function filterTutorial() {
    if (document.getElementById("tutor_pop_up") != null) {
        skip22(document.getElementById("tutor_pop_up"));
        window.scrollTo(0, 30);
    }
    clearSearchText();
    document.getElementById("searchbox").value = "Melanie Aceto";
    $('#search').click();
    clearSearchText();

    stage = new Stage();
    stage.initStage();
    var tutorshow2 = document.getElementById("TutorShowUp");
    tutorshow2.style.display = "block";
    tutorshow2.style.zIndex = "110";

    var elem = document.getElementById("FilterTutorWindow");
    elem.style.display = "block";
    elem.style.zIndex = "110";
    console.log("start2");
    stage.highLightById("topFilterBar");
    //skip tutor in filter first page
    var skipFilterWind = document.getElementById("skipFilter_rel");
    skipFilterWind.onclick = function () {
        Skip();
    }
    //click add filter
    var addFilterTutor = document.getElementById("addFilter medium-offset-1");
    addFilterTutor.onclick = function () {
        //remove tutor window
        document.getElementById("FilterTutorWindow").style.display = "none";

        filterPopup();
        //let filterwind = document.getElementById("FilterWindow");


        stage.highLightById("addGenre_popupSearch");
        var elem3 = document.getElementById("DisabledTutorWindow");
        elem3.style.display = "block";
        elem3.style.zIndex = "110";

        //filterwind.onclick = function () { filterPopup(); };
    }
    //after click add genre
    var FilterNextBotton3 = document.getElementById("addGenre_popupSearch");
    FilterNextBotton3.onclick = function () {
        document.getElementById("DisabledTutorWindow").style.display = "none";
        var elem3 = document.getElementById("DisabledTutorWindow2");
        elem3.style.display = "block";
        elem3.style.zIndex = "110";


        stage.highLightById("genre_popupSearchbox1");
        document.getElementById('genre_popupSearchbox1').value = "Contemporary or Modern";
        document.getElementById("genre_popupSearchbox1").disabled = true;

        var FilterNextBotton4 = document.getElementById("typeInModernNext");
        FilterNextBotton4.onclick = function () {

            document.getElementById("DisabledTutorWindow2").style.display = "none";
            var elem4 = document.getElementById("DisabledTutorWindow3");
            elem4.style.display = "block";
            elem4.style.zIndex = "110";
            stage.highLightById("search_popup");
            document.getElementById("genre_popupSearchbox1").disabled = false;
            var filterbot = document.getElementById("search_popup");
            filterbot.style.display = "block";
            filterbot.style.zIndex = "110";


        }
        FilterNextBotton3.onclick = function () { };
    }

    //click filter
    var FilterBotton1 = document.getElementById("search_popup");
    FilterBotton1.onclick = function () {
        window.scrollTo(0, 0);
        document.getElementById("genre_popupSearchbox1").oninput = function () { };
        document.getElementById("DisabledTutorWindow3").style.display = "none";
        var elem5 = document.getElementById("FiltorCongrat");
        elem5.style.display = "block";
        elem5.style.zIndex = "110";
        stage.highLightById("topFilterBar");
        document.getElementById("filterWindClose").disabled = false;
        FilterNextBotton3.onclick = function () { };
        FilterBotton1.onclick = function () { };
    }
    //congrat botton

    //congrat botton
    var FilterCongratSkip1 = document.getElementById('filterCongrat_rel');
    FilterCongratSkip1.onclick = function () {
        setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
        setCookie("first_time_completed", "true", cookies_exp_hours);
        location.reload();
    };



}


/**
 * Tutorial chapter I, Same with the filterTutorial(),
 */
function searchTutorial() {
    $("#clear").click();
    document.getElementById("TutorShowUp").style.display = "block";
    window.scrollTo(0, 0);
    stage.highLightById("searchbox_row");

    document.getElementById("Tutor").disabled = true;
    document.getElementById("search").disabled = true;
    document.getElementById("clear").disabled = true;
    document.getElementById("searchAll").disabled = true;
    document.getElementById("searchbox").disabled = true;
    document.getElementById("addGenreSearch").disabled = true;
    document.getElementById("addArtistTypeSearch").disabled = true;
    document.getElementById("addArtistSearch").disabled = true;
    document.getElementById("addCountrySearch").disabled = true;
    document.getElementById("addStateSearch").disabled = true;
    document.getElementById("addCitySearch").disabled = true;
    document.getElementById("addEthnicitySearch").disabled = true;
    document.getElementById("addGenderSearch").disabled = true;
    document.getElementById("foldAllSearch").disabled = true;
    var search2 = document.getElementById("search");
    var elem = document.getElementById("NetworkTutorWindow");
    elem.style.display = "block";
    elem.style.zIndex = "110";

    var skipNetWind1 = document.getElementById("skipNetwork_rel");
    skipNetWind1.onclick = function () {
        Skip();
        search2.onclick = function () { };
        /*search1.onclick=function(){};
            skipNetWind.checked = false;*/

    }
    var skipNetWind2 = document.getElementById("skipNetwork_rel2");
    skipNetWind2.onclick = function () {
        Skip();
        search2.onclick = function () { };

        /*search1.onclick=function(){};
            skipNetWind.checked = false;*/

    }

    //Next
    var networkNext = document.getElementById("NetworkTutorNext");
    networkNext.onclick = function () {
        document.getElementById("NetworkTutorWindow").style.display = "none";
        var elem15 = document.getElementById("NetworkTutorWindow1.5");
        elem15.style.display = "block";
        elem15.style.zIndex = "110";
        document.getElementById('searchbox').value = "Melanie Aceto";
        stage.highLightById("searchbox");
        document.getElementById("Tutor").disabled = false;
        document.getElementById("search").disabled = false;
        document.getElementById("clear").disabled = false;
        document.getElementById("searchAll").disabled = false;
        document.getElementById("addGenreSearch").disabled = false;
        document.getElementById("addArtistTypeSearch").disabled = false;
        document.getElementById("addArtistSearch").disabled = false;
        document.getElementById("addCountrySearch").disabled = false;
        document.getElementById("addStateSearch").disabled = false;
        document.getElementById("addCitySearch").disabled = false;
        document.getElementById("addEthnicitySearch").disabled = false;
        document.getElementById("addGenderSearch").disabled = false;
        document.getElementById("foldAllSearch").disabled = false;
    }
    //next
    var networkNext1 = document.getElementById("NetworkTutorNext1");
    networkNext1.onclick = function () {
        //document.getElementById("searchbox").disabled = false;
        window.scrollTo(0, 0);
        document.getElementById("NetworkTutorWindow1.5").style.display = "none";
        var elem20 = document.getElementById("NetworkTutorWindow2.0");
        elem20.style.display = "block";
        elem20.style.zIndex = "110";
        stage.highLightById("search");
        changeBackgroudColor(document.getElementById("search"));

        console.log("create arrow");
        arrowFromTo("NetworkTutorWindow2.0", "search");
        document.body.onresize = function (event) {
            $("#svg_arrow").hide();
            arrowFromTo("NetworkTutorWindow2.0", "search");
            stage.update();
        }
        document.body.onscroll = function (e) {
            window.scrollTo(0, 0);
            $("#svg_arrow").hide();
            arrowFromTo("NetworkTutorWindow2.0", "search");
            stage.update();

        }
        //setTimeout(changeBackgroudColor, 500);
    }
    var skipNetWind3 = document.getElementById("skipNetwork_rel3");
    skipNetWind3.onclick = function () {
        $("#svg_arrow").hide();
        Skip();

        search2.onclick = function () { };


    }



    search2.onclick = function () {
        //remove tutor window
        $("#svg_arrow").hide();
        document.body.onresize = function (event) {
            stage.update();
        }
        document.body.onscroll = function (e) {
            stage.update();
            console.log("scroll");

        }
        clearTimeout(timeoutFunc);
        $("#network_container").addClass("disabledbutton2");
        lineage_network.vis_net.setOptions({
            interaction: {
                dragView: false,
                dragNodes: false,
            }
        });
        document.getElementById('search').style.backgroundColor = "#21cb5a";
        document.getElementById("NetworkTutorWindow1.5").style.display = "none";
        document.getElementById("NetworkTutorWindow2.0").style.display = "none";
        var elem2 = document.getElementById("NetworkTutorWindow2");
        elem2.style.display = "block";
        elem2.style.zIndex = "110";
        document.getElementById("NetworkTutorWindow2").style.display = "block";
        stage.highLightById("network_container");
        search2.onclick = function () { };
    }
    //search in the window
    /*var search1 = document.getElementById("NetworkTutorSearch1");


    search1.onclick = function () {
        $("#svg_arrow").hide();
        clearTimeout(timeoutFunc);

        document.getElementById('search').style.backgroundColor = "#21cb5a";
        document.getElementById("NetworkTutorWindow1.5").style.display = "none";
        document.getElementById("NetworkTutorWindow2.0").style.display = "none";
        var elem2 = document.getElementById("NetworkTutorWindow2");
        elem2.style.display = "block";
        elem2.style.zIndex = "110";
        document.getElementById("NetworkTutorWindow2").style.display = "block";
        stage.highLightById("network_container");
        search2.onclick = function () { };


    }*/


    var network2GotIt = document.getElementById("NetworkTutorGotIt2");
    network2GotIt.onclick = function () {
        document.getElementById("NetworkTutorWindow2").style.display = "none";
        search2.onclick = function () { };
        setCookie("completed_first_chapter", "true", cookies_exp_hours);
        $("#network_container").removeClass("disabledbutton2");
        networkTutorial();
    }
    var networkSkip2 = document.getElementById("skipNetwork2_rel");
    networkSkip2.onclick = function () {
        $("#network_container").removeClass("disabledbutton2");
        Skip();
        search2.onclick = function () { };
    }
    var closeButtonEndChap = document.getElementById("close_NetworkTutorWindow2");
    closeButtonEndChap.onclick = function () {
        setCookie("completed_first_chapter", "true", cookies_exp_hours);
        $("#network_container").removeClass("disabledbutton2");
        closeAllWindows();
        lineage_network.vis_net.setOptions({
            interaction: {
                dragView: true,
                dragNodes: true,
            }
        });
    }

}

/**
 * A convenient function that close all Windows in chapter I and III
 * and then go back to default search.
 * If you don't want to go back to default search, use closeAllWindows2().
 */
function closeAllWindows() {

    stage.close();
    var hideDivs = document.getElementsByClassName("TutorAddFilter_popup");
    $("#network_container").removeClass("disabledbutton2");
    console.log(hideDivs);
    for (var i = 0; i < hideDivs.length; i++) {
        //hideDivs[i].style.visibility = "hidden"; // or
        hideDivs[i].style.display = "none"; // depending on what you're doing
    }
    $("#filter_div,#relation_check,.topFilterClass,.searchTextClass, #network_container, #small-6 column, #topFilter, #topFilter_text, #navbar, .small-7, .small-5, .footer").removeClass("disabledbutton");
    document.getElementById("FilterWindow").style.display = "none";
    document.getElementById("FirstTimeTutorialWindow").style.display = "none";
    document.getElementById("TutorShowUp").style.display = "none";
    document.getElementById("NetworkTutorWindow1.5").style.display = "none";
    document.getElementById("NetworkTutorWindow2.0").style.display = "none";
    document.getElementById("NetworkTutorWindow").style.display = "none";
    document.getElementById("NetworkTutorWindow2").style.display = "none";
    document.getElementById("svg_arrow").style.display = "none";
    document.getElementById("TutorWindow").style.display = "none";
    document.getElementById("FilterTutorWindow").style.display = "none";
    document.getElementById("DisabledTutorWindow2").style.display = "none";
    document.getElementById("FiltorCongrat").style.display = "none";
    document.getElementById("CompleteFirstChapter").style.display = "none";
    document.getElementById("CompleteSecondChapter").style.display = "none";
    document.getElementById("TutorWindowIfSkipped").style.display = "none";
    document.getElementById("ChaptersSelect").style.display = "none";
    document.getElementById("DisabledTutorWindow").style.display = "none";
    stage.close();
    document.getElementById("addFilter medium-offset-1").onclick = function () { filterPopup(); };
    document.getElementById("search").onclick = function () { };
    document.getElementById("search_popup").onclick = function () { };
    if (document.getElementById("genre_popupSearchbox1") != null) {
        document.getElementById("genre_popupSearchbox1").oninput = function () { };
    }

    document.getElementById("Tutor").disabled = false;
    document.getElementById("search").disabled = false;
    document.getElementById("clear").disabled = false;
    document.getElementById("searchAll").disabled = false;
    document.getElementById("searchbox").disabled = false;
    document.getElementById("addGenreSearch").disabled = false;
    document.getElementById("addArtistTypeSearch").disabled = false;
    document.getElementById("addArtistSearch").disabled = false;
    document.getElementById("addCountrySearch").disabled = false;
    document.getElementById("addStateSearch").disabled = false;
    document.getElementById("addCitySearch").disabled = false;
    document.getElementById("addEthnicitySearch").disabled = false;
    document.getElementById("addGenderSearch").disabled = false;
    document.getElementById("foldAllSearch").disabled = false;
    var FilterNextBotton3 = document.getElementById("addGenre_popupSearch");
    FilterNextBotton3.onclick = function () { }


    $("#clear").click();


}
/**
 * A convenient function that close all popups.
 * difference with closeAllWindow() is that this one doesn't go back to default search.
 * in the end this one use clearSearchText() instead of click "clear"
 * To avoid multiple search after one click.
 */
function closeAllWindows2() {
    console.log("closealwindowstart");
    stage.close();
    var hideDivs = document.getElementsByClassName("TutorAddFilter_popup");
    $("#network_container").removeClass("disabledbutton2");

    for (var i = 0; i < hideDivs.length; i++) {
        //hideDivs[i].style.visibility = "hidden"; // or
        hideDivs[i].style.display = "none"; // depending on what you're doing
    }
    $("#filter_div,#relation_check,.topFilterClass,.searchTextClass, #network_container, #small-6 column, #topFilter, #topFilter_text, #navbar, .small-7, .small-5, .footer").removeClass("disabledbutton");
    document.getElementById("FilterWindow").style.display = "none";
    document.getElementById("FirstTimeTutorialWindow").style.display = "none";
    document.getElementById("TutorShowUp").style.display = "none";
    document.getElementById("NetworkTutorWindow1.5").style.display = "none";
    document.getElementById("NetworkTutorWindow2.0").style.display = "none";
    document.getElementById("NetworkTutorWindow").style.display = "none";
    document.getElementById("NetworkTutorWindow2").style.display = "none";
    document.getElementById("svg_arrow").style.display = "none";
    document.getElementById("TutorWindow").style.display = "none";
    document.getElementById("FilterTutorWindow").style.display = "none";
    document.getElementById("DisabledTutorWindow2").style.display = "none";
    document.getElementById("FiltorCongrat").style.display = "none";
    document.getElementById("CompleteFirstChapter").style.display = "none";
    document.getElementById("CompleteSecondChapter").style.display = "none";
    document.getElementById("TutorWindowIfSkipped").style.display = "none";
    document.getElementById("ChaptersSelect").style.display = "none";
    document.getElementById("DisabledTutorWindow").style.display = "none";
    stage.close();
    document.getElementById("addFilter medium-offset-1").onclick = function () { filterPopup(); };
    document.getElementById("search").onclick = function () { };
    document.getElementById("search_popup").onclick = function () { };
    if (document.getElementById("genre_popupSearchbox1") != null) {
        document.getElementById("genre_popupSearchbox1").oninput = function () { };
    }
    document.getElementById("Tutor").disabled = false;
    document.getElementById("search").disabled = false;
    document.getElementById("clear").disabled = false;
    document.getElementById("searchAll").disabled = false;
    document.getElementById("searchbox").disabled = false;
    document.getElementById("addGenreSearch").disabled = false;
    document.getElementById("addArtistTypeSearch").disabled = false;
    document.getElementById("addArtistSearch").disabled = false;
    document.getElementById("addCountrySearch").disabled = false;
    document.getElementById("addStateSearch").disabled = false;
    document.getElementById("addCitySearch").disabled = false;
    document.getElementById("addEthnicitySearch").disabled = false;
    document.getElementById("addGenderSearch").disabled = false;
    document.getElementById("foldAllSearch").disabled = false;
    var FilterNextBotton3 = document.getElementById("addGenre_popupSearch");
    FilterNextBotton3.onclick = function () { }


    clearSearchText();
    console.log("closealwindowend");

}
// This function will be called when 'Skip Tutorial' button is clicked/tapped.
// It will delete tutorial div, cancel highlight effect, disable all tutortial mouse events, and resotre all functionlities of original social graph

/**
 * Use Skip() to end Chapter I and III.
 * Use skip(popup) to end Chapter II.
 * skip22(popup) does not go back to default search. to avoid search twice.
 */
// Use this Skip() to end chapter I and III
function Skip() {
    setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
    closeAllWindows();
    clearTimeout(timeoutFunc);
    document.getElementById('search').style.backgroundColor = "#21cb5a";
    lineage_network.vis_net.setOptions({
        interaction: {
            dragView: true,
            dragNodes: true,
        }
    });
}
// Use this skip(popup) to end chapter II
function skip(popup) {
    //location.reload();
    $("#AddRelationWindow").hide();
    $("#spin_loading_relation").show();
    $("#AddRelationWindow_content").hide();
    $("#clear").click();
    document.getElementById("TutorShowUp").style.display = "none";
    document.getElementById("searchbox").disabled = false;
    stage.close();
    document.getElementById(popup.id).remove();
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.off('selectEdge');
    lineage_network.vis_net.off('dragEnd');
    lineage_network.vis_net.off('zoom');
    lineage_network.vis_net.off('oncontext');

    lineage_network.vis_net.on('selectNode', function (obj) {
        lineage_network.leftClickEvent(obj);
    });

    lineage_network.vis_net.on('oncontext', function (obj) {
        lineage_network.rightMeunEvent(obj);
    });

    lineage_network.vis_net.on('hoverEdge', function (obj) {
        lineage_network.hoverEdgeEvent(obj);
    });

    lineage_network.vis_net.on('blurEdge', function (obj) {
        $("#" + lineage_network.conatiner_id).css("cursor", "grab");
    });

    document.getElementById('closeNav').onclick = function () { document.getElementById('mySidenav').style.display = 'none'; };
    $("body").css("overflow", "auto");
    lineage_network.vis_net.setOptions({
        interaction: {
            dragView: true,
            dragNodes: true,
        }
    });
    document.getElementById('searchRelation').onclick = function (object) { };
    document.getElementById('hideRelation').onclick = function (object) { };
    document.getElementById('Event').onclick = function (object) { };
    enableNetworkInteraction();
    //document.getElementById('searchRelation').removeEventListener('click', right);
}


// skip22(popup) does not go back to default search. to avoid search twice.
function skip22(popup) {
    //location.reload();
    $("#AddRelationWindow").hide();
    $("#spin_loading_relation").show();
    $("#AddRelationWindow_content").hide();
    clearSearchText();
    document.getElementById("TutorShowUp").style.display = "none";
    document.getElementById("searchbox").disabled = false;
    stage.close();
    document.getElementById(popup.id).remove();
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.off('selectEdge');
    lineage_network.vis_net.off('dragEnd');
    lineage_network.vis_net.off('zoom');
    lineage_network.vis_net.off('oncontext');

    lineage_network.vis_net.on('selectNode', function (obj) {
        lineage_network.leftClickEvent(obj);
    });

    lineage_network.vis_net.on('oncontext', function (obj) {
        lineage_network.rightMeunEvent(obj);
    });

    lineage_network.vis_net.on('hoverEdge', function (obj) {
        lineage_network.hoverEdgeEvent(obj);
    });

    lineage_network.vis_net.on('blurEdge', function (obj) {
        $("#" + lineage_network.conatiner_id).css("cursor", "grab");
    });

    document.getElementById('closeNav').onclick = function () { document.getElementById('mySidenav').style.display = 'none'; };
    $("body").css("overflow", "auto");
    lineage_network.vis_net.setOptions({
        interaction: {
            dragView: true,
            dragNodes: true,
        }
    });
    document.getElementById('searchRelation').onclick = function (object) { };
    document.getElementById('hideRelation').onclick = function (object) { };
    document.getElementById('Event').onclick = function (object) { };
    enableNetworkInteraction();
    //document.getElementById('searchRelation').removeEventListener('click', right);
}

/**
 * Add skip button in the network tutorial
 * @param popup
 * @param text
 */
function addSkip(popup, text) {
    var skipButton = document.createElement('button');
    skipButton.textContent = text;
    skipButton.className = "closeButtonFilter";
    skipButton.addEventListener('click', (function (e) { skip(popup) }));
    popup.appendChild(skipButton);
}
/**
 * Add progress bar in the network tutorial
 * @param popup
 */
function addProgress(popup) {
    var divs = document.createElement("div");

    divs.className = "progress";
    divs.id = "progress1";

    popup.appendChild(divs);
}
/**
 * Clear all existed input in search boxes on the left, or tutorial will be messed up.
 */
function clearSearchText() {
    document.getElementById('searchbox').value = "";
    clearByClass('addArtist', 'Artist');
    clearByClass('addGenre', 'Genre');
    clearByClass('addArtistType', 'ArtistType');
    clearByClass('addCountry', 'Country');
    clearByClass('addState', 'State');
    clearByClass('addCity', 'City');
    clearByClass('addEthnicity', 'Ethnicity');
    clearByClass('addGender', 'Gender');
    resetBorderColourByTheirClass("addGenre");
    resetBorderColourByTheirClass("addCountry");
    resetBorderColourByTheirClass("addState");
    resetBorderColourByTheirClass("addCity");

}

function clearByClass(classname, frontID) {
    for (var item of document.getElementsByClassName(classname)) {
        if (item.id.includes('Searchbox')) {

        }
    }
    if (classname != 'addArtist') {
        document.getElementById(classname + 'Label').style.display = 'none';
    }

}
/**
 * This will reset border colour for input textbox that also call autocompleteAtrributes() in lineage_network_default.js
 * @param {className} classNameToReset
 */
function resetBorderColourByTheirClass(classNameToReset) {
    var target = document.getElementsByClassName(classNameToReset);
    for (i = 0; i < target.length; i++) {
        target[i].style.borderColor = 'black';
    }

}

/**
 * Bind function to the clickable bar for the network tutorial(chapter II).
 * chapter I and III progress bar functions are in lineage_index.php. (search for "completeone")
 * complete111,complete222,complete333 for 3 clickable progress bar.
 * @author Zeping Wang
 * @author Hanzhang Bai
 * @param popup
 */
function addProgressBarButtons(popup) {
    var complete111 = document.getElementsByClassName('complete1');

    for (var i = 0; i < complete111.length; i++) {
        complete111[i].onclick = function () {
            $(".custom-menu").hide();
            window.scrollTo(0, 0);
            clearSearchText();
            skip22(popup);


            setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);

            clearTimeout(timeoutFunc);
            document.getElementById('search').style.backgroundColor = "#21cb5a";

            closeAllWindows2();
            var tutorshow = document.getElementById("TutorShowUp");
            tutorshow.style.display = "block";
            tutorshow.style.zIndex = "110";
            searchTutorial();
        };
    }

    var complete222 = document.getElementsByClassName('complete2');

    for (var i = 0; i < complete222.length; i++) {
        complete222[i].onclick = function () {
            $(".custom-menu").hide();

            skip22(popup);

            document.getElementById("svg_arrow").display = "none";
            setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
            closeAllWindows2();
            clearTimeout(timeoutFunc);

            var search2 = document.getElementById("search");
            search2.onclick = function () { };

            document.getElementById("searchbox").value = "Melanie Aceto";
            $('#search').click();

            window.scrollTo(0, 190);
            var tutorshow = document.getElementById("TutorShowUp");
            tutorshow.style.display = "block";
            tutorshow.style.zIndex = "110";
            networkTutorial();
        };
    }
    var complete333 = document.getElementsByClassName('complete3');

    for (var i = 0; i < complete333.length; i++) {
        complete333[i].onclick = function () {
            $(".custom-menu").hide();
            window.scrollTo(0, 30);
            setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
            closeAllWindows2();
            clearTimeout(timeoutFunc);
            skip22(popup);
            document.getElementById("svg_arrow").style.display = "none";
            var tutorshow = document.getElementById("TutorShowUp");
            tutorshow.style.display = "block";
            tutorshow.style.zIndex = "110";
            console.log("start");
            filterTutorial();
        };
    }
}

/**
 * The network tutorial driver function that should be called by tutorChapterInit() or tutorialWelcome()
 */
function networkTutorial() {
    window.scrollTo(0, 190);
    stage.highLightById("network_container");

    var popup = document.createElement('div');
    popup.id = "tutor_pop_up";
    document.body.appendChild(popup);

    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer;color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:40%\"></div></div><br><p> Click and drag on the white area to explore the network </p>\n";
    popup.className = 'networkHint';
    popup.style.zIndex = 110;
    popup.style.position = 'absolute'
    popup.style.top = "50%";
    popup.style.left = "100px";
    var filterNext = document.createElement('button');
    filterNext.className = "BoxTutorButton";
    filterNext.textContent = "Next";
    filterNext.addEventListener('click', (function (e) {
        filterNext.remove();
        moveToNode('548', function () {
            enableNetworkInteraction();
            lineage_network.vis_net.setOptions({
                interaction: {
                    dragView: false,
                    dragNodes: false,
                }
            });
            zoomTutorial(popup);
        });
    }));
    popup.appendChild(filterNext);


    $("body").css("overflow", "hidden");
    // var dummy=new LineageNetwork();
    lineage_network.vis_net.setOptions({
        interaction: {
            dragView: true,
            dragNodes: true,
        }
    });

    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.off('oncontext');
    // lineage_network.vis_net.on('dragEnd', function (obj) {
    //     if (obj.edges.length == 0 && obj.nodes.length == 0) {
    //         lineage_network.vis_net.off('dragEnd');

    //         zoomTutorial(popup);
    //     } else {
    //         cameraFocus(popup, "red", "#006400");
    //     }
    // });



    addSkip(popup, "X");
    addProgressBarButtons(popup);


}

//Zoom tutorial
function zoomTutorial(popup) {

    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:42%\"></div></div><p> Scroll your mouse wheel to zoom<br>(If you are using touchpad, slide two fingers up or down) </p>\n";

    addSkip(popup, 'X');
    addProgressBarButtons(popup);
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.off('selectEdge');
    var filterNext = document.createElement('button');
    filterNext.className = "BoxTutorButton";
    filterNext.textContent = "Next";
    filterNext.addEventListener('click', (function (e) {
        filterNext.remove();
        moveToNode('548', function () {
            disableNetworkInteraction();
            startLeftClick(popup);
        });
    }));
    popup.appendChild(filterNext);
    // lineage_network.vis_net.on('zoom', function () {
    //     lineage_network.vis_net.off('zoom');
    //     //alert("You can click \"Next\" to continue");
    // });

}

function startLeftClick(popup) {
    setTimeout(function () {
        leftClickTutorial(popup);
        //console.log("second function executed");
    }, 1100);
    addProgressBarButtons(popup);
}

function leftClickTutorial(popup) {
    document.oncontextmenu = document.body.oncontextmenu = function () { return false; }
    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:44%\"></div></div><p>Left click on the node to see detail information of artist </p>\n";
    addSkip(popup, 'X');
    addProgressBarButtons(popup);
    var nodeDom = new networkElement(548);
    stage.LightedDom = nodeDom;
    stage.update();
    // lineage_network.vis_net.off('hoverEdge');
    // lineage_network.vis_net.off('oncontext');
    // lineage_network.vis_net.off('selectEdge');
    // lineage_network.vis_net.off('zoom');

    // lineage_network.vis_net.on('oncontext', function(obj){
    //     cameraFocus(popup,"red","#006400");
    // });
    lineage_network.vis_net.on('selectNode', function (obj) {
        document.oncontextmenu = document.body.oncontextmenu = function () { return true; }
        lineage_network.leftClickEvent(obj);
        stage.highLightById("mySidenav");
        //document.getElementById('artist_biography').style.pointerEvents = 'none';
        popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
            "\n" +
            "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
            "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
            "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
            "\n" +
            "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:46%\"></div></div><p>Now you can view the information of Eun-Kyung Chung on the right hand side.  <br><span style=\"font-size: 22px;font-weight:bold\">Close the right hand side window to continue.</span> </p>\n";
        addSkip(popup, "X");
        addProgressBarButtons(popup);
        document.getElementById('closeNav').onclick = function () { startRightClickTutorial(popup) };
    });

}

/**
 * Move to node with nid, after movement call the call_back function
 * @param nid
 * @param call_back
 */
function moveToNode(nid, call_back) {
    var pos = lineage_network.vis_net.getPositions(nid)[nid];
    var nodePos = {
        position: pos,
        scale: 1.0,
        offset: { x: 0, y: 0 },
        animation: {
            duration: 1000,
            easingFunction: "easeInOutQuad"
        }
    }
    disableNetworkInteraction();
    lineage_network.vis_net.moveTo(nodePos);
    lineage_network.vis_net.once("animationFinished", function () {
        enableNetworkInteraction();
        console.log("animationFinished ");
        call_back();
    });
}
function startRightClickTutorial(popup) {


    moveToNode('548', function () {
        disableNetworkInteraction();
        rightClickTutorial(popup);
    });
    addProgressBarButtons(popup);
}

/**
 * disable network interaction for user
 *
 */
function disableNetworkInteraction() {

    lineage_network.vis_net.setOptions({
        interaction: {
            dragView: false,
            dragNodes: false,
            zoomView: false,
        }
    });
    console.log("disable interaction");


}

/**
 * enable network interaction for user
 *@author Sai Cao
 */
function enableNetworkInteraction() {
    lineage_network.vis_net.setOptions({
        interaction: {
            dragView: true,
            dragNodes: true,
            zoomView: true,
        }
    });
    console.log("enable interaction ");
}

/**
 * disable network context menu will disappear after click other element
 * @author Sai Cao
 */
function disableRightClickHideEvent() {
    console.log("disable RightClickHideEvent");
    $(document).off("click", closeNodeMenuEvent);

    ForceClickClass("custom-menu", function () {
        console.log("true click");
        $(".custom-menu").hide();
    }, function () {
        console.log("false click");
    });
}

/**
 * This is event function fired when use click the DOM with the class name. if not false_call_back will be excute
 * @param {string}class_name
 * @param {function}true_call_back
 * @param {function}false_call_back
 * @author Sai Cao
 */
function ForceClickClass(class_name, true_call_back, false_call_back) {
    $(document).on("click", function ForceClick(e) {
        // If the clicked element is not the menu
        if ($(e.target).parents("." + class_name).length > 0) {
            $(document).off("click", ForceClick);
            true_call_back();

        } else {
            false_call_back();
        }
    });
}

/**
 * Not used for now
 * @param id
 * @param call_black
 *
 */

function ForceClickTarget(id, call_black) {
    $(document).on("click", function (e) {
        // If the clicked element is not the menu
        if (!$(e.target).attr('id') != id) {
            call_black();
        }
    });
}

/**
 * enable network context menu will disappear after click other element
 * @author Sai Cao
 */
function enableRightClickHideEvent() {
    console.log("enable RightClickHideEvent");
    $(".custom-menu").hide();
    $(document).off("click");
}
function rightClickTutorial(popup) {
    document.getElementById('mySidenav').style.display = 'none';
    document.getElementById('closeNav').onclick = function () { document.getElementById('mySidenav').style.display = 'none'; };
    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:48%\"></div></div><p> Try to right click on the highlighted node.<br>(If you are using touchpad, click or tap it with two fingers)</p>\n";
    addSkip(popup, "X");
    addProgressBarButtons(popup);
    var nodeDom = new networkElement(548);
    stage.LightedDom = nodeDom;
    stage.update();
    lineage_network.vis_net.off('oncontext');
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.off('oncontext');
    lineage_network.vis_net.on('oncontext', function (obj) {


        console.log(obj);
        lineage_network.rightMeunEvent(obj);

        var selected = lineage_network.vis_net.getNodeAt(obj.pointer.DOM);
        if (selected == undefined) {
            return;
        }
        disableRightClickHideEvent();
        stage.highLightById("searchRelation");
        popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
            "\n" +
            "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
            "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
            "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
            "\n" +
            "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:50%\"></div></div><p> Now you can search the relations of <br>Eun-Kyung Chung  <br><span style=\"font-size: 22px;font-weight:bold\">by clicking on the highlighted part of menu.</span></p>\n";
        addSkip(popup, "X");
        addProgressBarButtons(popup);
        document.getElementById('searchRelation').onclick = function (object) {
            lineage_network.vis_net.off('zoom');
            lineage_network.searchAndAddArtistEvent({ action: "centerSearchById", "artist_profile_id": [lineage_network.vis_net.getNodeAt(obj.pointer.DOM)] });
            $(".custom-menu").hide();
            stage.highLightById("network_container");
            popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
                "\n" +
                "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
                "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
                "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
                "\n" +
                "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:52%\"></div></div><p> Congratulations, you are viewing the relationship of Eun-Kyung Chung right now.</p>  \n";
            addSkip(popup, "X");
            addProgressBarButtons(popup);
            var loading = document.createElement("p")
            loading.innerHTML = "<span style='color: red;'>(Loading next chapter, please wait...)</span>";

            popup.appendChild(loading);
            lineage_network.vis_net.once("startStabilizing", function () {
                setTimeout(function () {
                    console.log("timout");
                    lineage_network.vis_net.stopSimulation();
                }, 4000);
            });
            lineage_network.vis_net.once("stabilized", function () {
                loading.remove();
                var filterNext = document.createElement('button');
                filterNext.className = "BoxTutorButton";
                filterNext.textContent = "Next";
                filterNext.addEventListener('click', (function (e) {
                    filterNext.remove();
                    startHideRelationshipTutorial(popup);
                }));
                popup.appendChild(filterNext);
            });

        };
        lineage_network.vis_net.off('oncontext');

    });

}
function startHideRelationshipTutorial(popup) {
    moveToNode('548', function () {
        disableNetworkInteraction();
        hideRelationshipTutorial(popup);
    });
    addProgressBarButtons(popup);
}
function hideRelationshipTutorial(popup) {
    document.getElementById('mySidenav').style.display = 'none';
    document.getElementById('closeNav').onclick = function () { document.getElementById('mySidenav').style.display = 'none'; };
    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:54%\"></div></div><p> Try to right click on the highlighted node again.</p>\n";
    addSkip(popup, "X");
    addProgressBarButtons(popup);

    var nodeDom = new networkElement(548);
    stage.LightedDom = nodeDom;
    stage.update();
    lineage_network.vis_net.off('oncontext');
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.on('oncontext', function (obj) {
        console.log(obj);
        lineage_network.rightMeunEvent(obj);
        var selected = lineage_network.vis_net.getNodeAt(obj.pointer.DOM);
        if (selected == undefined) {
            return;
        }

        i = 1;
        stage.highLightById("hideRelation");
        disableRightClickHideEvent();
        popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
            "\n" +
            "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
            "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
            "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
            "\n" +
            "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:56%\"></div></div><p> Now you can hide relationship of <br>Eun-Kyung Chung  <br><span style=\"font-size: 22px;font-weight:bold\">by click on the highlighted part of menu.</span></p>\n";
        addSkip(popup, "X");
        addProgressBarButtons(popup);
        document.getElementById('hideRelation').onclick = function (object) {
            enableNetworkInteraction();
            lineage_network.vis_net.off('oncontext');
            lineage_network.vis_net.off('zoom');
            //lineage_network.searchAndAddArtistEvent({ action: "centerSearchById", "artist_profile_id": [lineage_network.vis_net.getNodeAt(obj.pointer.DOM)] });
            $(".custom-menu").hide();
            stage.highLightById("network_container");
            popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
                "\n" +
                "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
                "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
                "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
                "\n" +
                "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:58%\"></div></div><p>Congratulations, now all relationships of Eun-Kyung Chung are hidden.</p>\n";
            addSkip(popup, "X");
            addProgressBarButtons(popup);
            var filterNext = document.createElement('button');
            filterNext.className = "BoxTutorButton";
            filterNext.textContent = "Next";
            filterNext.addEventListener('click', (function (e) {
                enableNetworkInteraction();
                filterNext.remove();
                //setCookie("completed_second_chapter", "true", cookies_exp_hours);
                moveToNode('548', function () {
                    disableNetworkInteraction();
                    eventTutorial(popup);
                });

            }));
            popup.appendChild(filterNext);
        };
    });

}
function eventTutorial(popup) {
    document.getElementById('mySidenav').style.display = 'none';
    document.getElementById('closeNav').onclick = function () { document.getElementById('mySidenav').style.display = 'none'; };
    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:60%\"></div></div><p> Try to right click on the highlighted node again.</p>\n";
    addSkip(popup, "X");
    addProgressBarButtons(popup);

    var nodeDom = new networkElement(548);
    stage.LightedDom = nodeDom;
    stage.update();
    lineage_network.vis_net.off('oncontext');
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.on('oncontext', function (obj) {
        console.log(obj);
        lineage_network.rightMeunEvent(obj);
        var selected = lineage_network.vis_net.getNodeAt(obj.pointer.DOM);
        if (selected == undefined) {
            return;
        }
        i = 1;
        stage.highLightById("Event");
        disableRightClickHideEvent();
        popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
            "\n" +
            "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
            "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
            "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
            "\n" +
            "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:62%\"></div></div><p> Now you show <br>Eun-Kyung Chung's  <br><span style=\"font-size: 22px;font-weight:bold\"> event by click on the highlighted part of menu.</span></p>\n";
        addSkip(popup, "X");
        addProgressBarButtons(popup);
        document.getElementById('Event').onclick = function (object) {
            lineage_network.vis_net.off('oncontext');
            lineage_network.vis_net.off('zoom');
            //lineage_network.searchAndAddArtistEvent({ action: "centerSearchById", "artist_profile_id": [lineage_network.vis_net.getNodeAt(obj.pointer.DOM)] });
            $(".custom-menu").hide();
            stage.highLightById("network_container");
            popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
                "\n" +
                "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
                "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
                "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
                "\n" +
                "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:64%\"></div></div><p>Congratulations, all Eun-Kyung Chung's events are listed here.</p>\n";
            addSkip(popup, "X");
            addProgressBarButtons(popup);
            var filterNext = document.createElement('button');
            filterNext.className = "BoxTutorButton";
            filterNext.textContent = "Next";
            filterNext.addEventListener('click', (function (e) {
                $('#EventPopUp').hide();
                //setCookie("completed_second_chapter", "true", cookies_exp_hours);
                moveToNode('548', function () {
                    disableNetworkInteraction();
                    addRelationshipTutorial(popup);
                });
            }));
            popup.appendChild(filterNext);

        };

    });

}
function addRelationshipTutorial(popup) {
    document.getElementById('mySidenav').style.display = 'none';
    $('#EventPopUp').hide();
    document.getElementById('closeNav').onclick = function () { document.getElementById('mySidenav').style.display = 'none'; };
    popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
        "\n" +
        "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
        "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
        "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
        "\n" +
        "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:66%\"></div></div><p> Try to right click on the highlighted node again.</p>\n";
    addSkip(popup, "X");
    addProgressBarButtons(popup);
    var nodeDom = new networkElement(548);
    stage.LightedDom = nodeDom;
    stage.update();
    lineage_network.vis_net.off('oncontext');
    lineage_network.vis_net.off('selectNode');
    lineage_network.vis_net.on('oncontext', function (obj) {

        console.log(obj);
        lineage_network.rightMeunEvent(obj);
        var selected = lineage_network.vis_net.getNodeAt(obj.pointer.DOM);
        if (selected == undefined) {
            return;
        }
        i = 1;
        stage.highLightById("AddRelation");
        disableRightClickHideEvent();
        popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
            "\n" +
            "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
            "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
            "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
            "\n" +
            "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:68%\"></div></div><p> Now you can add <br>Eun-Kyung Chung to your network <br><span style=\"font-size: 22px;font-weight:bold\">by click on the highlighted part of menu.</span></p>\n";
        addSkip(popup, "X");
        addProgressBarButtons(popup);
        document.getElementById('AddRelation').onclick = function (object) {
            lineage_network.vis_net.off('oncontext');
            lineage_network.vis_net.off('zoom');
            //lineage_network.searchAndAddArtistEvent({ action: "centerSearchById", "artist_profile_id": [lineage_network.vis_net.getNodeAt(obj.pointer.DOM)] });
            $(".custom-menu").hide();
            stage.highLightById("network_container");
            $("#AddRelationWindow").show();
            $("#spin_loading_relation").hide();
            $("#AddRelationWindow_content").show();
            popup.innerHTML = "<div class=\"currentChapterText\">Tutorial Chapter II: Visualization Network</div><ul class=\"progressbar\">\n" +
                "\n" +
                "                        <li class=\"complete1\" style=\"cursor: pointer; color: black;--my-color-var: white\"><p style=\'font-size:12pt\'>Search</p></li>\n" +
                "                        <li class=\"complete2\" style=\"cursor: pointer; color: black;--my-color-var: #21CB5A;\"><p style=\'font-size:12pt\'>Network</p></li>\n" +
                "                        <li class=\"complete3\" style=\"cursor: pointer; color: black;--my-color-var: white;\"><p style=\'font-size:12pt\'>Filter</p></li>\n" +
                "\n" +
                "                    </ul><div class=\"progress\"><div class=\"progress-bar\" style=\"width:68%\"></div></div><p>This is where you could add an artist from the network to your lineage.<br>(Clicking on relationships with Eun-Kyung within this tutorial will not add her to your lineage.)</p>\n";
            addSkip(popup, "X");

            addProgressBarButtons(popup);
            var filterNext = document.createElement('button');
            filterNext.className = "BoxTutorButton";
            filterNext.textContent = "Next Chapter";
            filterNext.addEventListener('click', (function (e) {
                setCookie("completed_second_chapter", "true", cookies_exp_hours);
                document.getElementById('searchRelation').onclick = function (object) { };
                document.getElementById('hideRelation').onclick = function (object) { };
                document.getElementById('Event').onclick = function (object) { };
                filterTutorial();
            }));
            popup.appendChild(filterNext);

        };
        lineage_network.vis_net.off('oncontext');

    });

}

/**
 * This class just provide update function of Network node for highlight feature.
 * @author Sai Cao
 */
class networkElement {

    constructor(nid) {
        this.nid = nid;
    }
    getBoundingClientRect() {
        lineage_network.vis_net.stopSimulation();
        let rect = lineage_network.vis_net.getBoundingBox(this.nid);
        let l = lineage_network.vis_net.canvasToDOM({ x: rect.left, y: rect.top });
        let d = lineage_network.vis_net.canvasToDOM({ x: rect.right, y: rect.bottom });
        // var net=document.getElementById(lineage_network.container.id).children[0];
        let net = lineage_network.container.children[0];
        let abs = net.getBoundingClientRect();

        return {
            top: abs.top + l.y,
            left: abs.left + l.x,
            right: abs.left + d.x,
            bottom: abs.top + d.y
        };
    }
}


function changeBorderColor(dom, start_color, end_color) {

    dom.style.borderColor = start_color;
    const elementRect = dom.getBoundingClientRect();


    setTimeout(function () {

        dom.style.borderColor = end_color;
    }, 3000);
}

function changeBackgroudColor(dom) {

    if (dom.style.backgroundColor == 'yellow') {
        dom.style.backgroundColor = '#21CB5A';
    } else {
        dom.style.backgroundColor = 'yellow';
    }

    timeoutFunc = setTimeout(function () { changeBackgroudColor(dom) }, 500);
}


/**
 *
 * scroll to element and change the border of element
 * @param {Object} dom DOM element to focus
 * @param {string}start_color color before changing
 * @param {string}end_color color after changing
 * @author Sai Cao
 */
function cameraFocus(dom, start_color, end_color) {

    dom.style.borderColor = start_color;
    const elementRect = dom.getBoundingClientRect();
    const absoluteElementTop = elementRect.top + window.pageYOffset;
    const middle = absoluteElementTop - (window.innerHeight / 2);
    window.scrollTo(0, middle);
    setTimeout(function () {

        dom.style.borderColor = end_color;
    }, 3000);
}


/**
 * Draw arrow in tutorial.
 *
 * @param {string} from_id DOM id
 * @param {string} to_id DOM to be pointed by arrow
 * @author Sai Cao
 */
function arrowFromTo(from_id, to_id) {

    let arrow_svg = document.getElementById('svg_arrow');
    var from_rect = document.getElementById(from_id).getBoundingClientRect();
    var to_rect = document.getElementById(to_id).getBoundingClientRect();
    arrow_svg.style.display = "inline-block";
    console.log(from_rect);
    console.log(to_rect);


    arrow_svg.style.left = parseInt(to_rect.right) + "px";
    arrow_svg.style.top = parseInt(from_rect.top) + "px";
    console.log(parseInt(from_rect.right - to_rect.left) + "px");
    arrow_svg.style.width = parseInt(from_rect.left - to_rect.right) + "px";

    arrow_svg.style.height = parseInt(to_rect.top - from_rect.top) + "px";
    console.log(parseInt(to_rect.top - from_rect.top) + "px");
    console.log(arrow_svg);
}


/**
 * a class to highlight elements
 * @author
 */
class Stage {


    constructor() {
        this.Doms = new Map();
        this.LightedDom = undefined;
        this.arrowDom = undefined;
    }


    /**
     * create events of  tutorial stage
     */
    initStage() {

        let self = this
        document.body.onscroll = function () {
            self.update();

        }
        document.body.onresize = function () {
            self.update();

        }
        document.body.onclick = function () {
            self.update();
        }
    }

    /**
     * Not used by this project now
     * @param dom
     * @author Sai Cao
     */
    stageAddComponent(dom) {
        this.Doms.set(dom.id, dom);
        dom.style.zIndex = "110";
        document.body.appendChild(dom);
    }

    /**
     * Call to clear elements and event for tutorial stage
     * Ex:stage highlight
     * @author Sai Cao
     */
    close() {
        document.body.onscroll = "";
        document.body.onresize = "";
        document.body.onclick = "";
        this.Doms.forEach(function (value, key) {
            // console.log(key);
            document.body.removeChild(value);
        });
        this.Doms.clear();
    }

    /**
     * Change DOM highlight element by it's id
     * @param {string} id
     */
    highLightById(id) {

        // var rect = element.getBoundingClientRect();

        this.LightedDom = document.getElementById(id);
        this.update();
    }


    /**
     * update elements on tutorial stage
     * call by windows resize and onscroll
     * @author Sai Cao
     */
    update() {

        this.Doms.forEach(function (value, key) {
            // console.log(key);
            document.body.removeChild(value);
        });
        this.Doms.clear();
        if (this.LightedDom != undefined) {
            let rect = this.LightedDom.getBoundingClientRect();
            this.updateLightRect(rect.top, rect.left, rect.right, rect.bottom);
        }
        if (this.arrowDom != undefined) {
            this.arrowDom.update();
        }
    }

    /**
     *
     *private function for create rectangle cover other dom elements except highlight area
     * @param {number} top
     * @param {number} left
     * @param {number} right
     * @param  {number} bottom
     * @author Sai Cao
     */
    updateLightRect(top, left, right, bottom) {
        // var l_stage = document.createElement("div");
        this.createWarpper("l_stage", 0, top, left, bottom - top);
        this.createWarpper("r_stage", right, top, document.body.clientWidth, bottom - top);
        this.createWarpper("t_stage", 0, 0, document.body.clientWidth, top);
        this.createWarpper("b_stage", 0, bottom, document.body.clientWidth, document.body.clientHeight - bottom);
    }

    /**
     *
     * create rectangles to cover other elements excepted highlighted
     * elements and tutorial elements
     * @param {string }id
     * @param {number}x
     * @param {number}y
     * @param {number}dx
     * @param {number}dy
     */
    createWarpper(id, x, y, dx, dy) {
        // console.log(id, x, y, dx, dy);
        let div = document.createElement("div");
        div.id = id;
        div.style.position = "fixed";
        div.style.left = x;
        div.style.top = y;
        div.style.width = dx;
        div.style.height = dy;
        div.style.backgroundColor = "black";
        div.style.zIndex = 100;
        div.style.opacity = "0.4";
        this.Doms.set(id, div);
        document.body.appendChild(div);
    }
}



