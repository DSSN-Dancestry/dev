<?php
require 'util.php';
require 'utils.php';
my_session_start();
checkAdmin();
?>

<!DOCTYPE html>

<body style="text-align: center">
    <?php
    include 'menu.php';
    ?>
    <title>Update Network Cache | Dancestry</title>
    <div id="adnub_display_div" class="mrt10i row">
        <div id="tab_bar_row" class="row tab">
            <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="event" onclick="window.location.href = 'delete_user.php';">Maintain Users
            </button>
            <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="add_event" onclick="window.location.href = 'maintain_genres.php';">Maintain Genres
            </button>
            <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="feature_event" onclick="window.location.href = 'feature_management.php';">Feature Management
            </button>
            <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" onclick="window.location.href = 'maintain_network.php';">Update Network Cache
            </button>
            <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" onclick="window.location.href = 'maintain_logs.php';">Admin Logs
            </button>
        </div>
    </div>
    <script type="text/javascript" src="./js/lineage_network_default.js"></script>
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <script>
        function dumpNetwork() {
            network.vis_net.stopSimulation();
            let dump_obj = network.vis_net.getPositions();
            let json_dump = JSON.stringify(dump_obj);
            $.ajax({
                type: "POST",
                url: "./network_cache_controller.php",
                data: json_dump,
                success: function(data) {
                    alert(data);
                },
                error: function(xhr, status, err) {
                    console.log(xhr.responseText);
                },
                dataType: "json",
                contentType: "application/json"
            });
        }
    </script>
    <div>
        <button style="background-color: #1dc116;width: 200px;height: 50px" onclick="dumpNetwork()">Export Network</button>

    </div>


    <div id="network_container">

        <img id="spin_loading" src="./img/Spin_256.gif" alt="Loading please Wait" style="margin-top: 300px;">
        <div id="my_network">
        </div>
        <div id="loadingBar">
            <div class="outerBorder">
                <div id="progress_text">0%</div>
                <div id="progress_border">
                    <div id="progress_bar"></div>
                </div>
            </div>
        </div>
    </div>



    <script>
        var network = undefined;
        window.onload = function() {
            const default_options = {

                // configure: {},    // defined in the configure module.
                edges: {
                    smooth: {
                        enabled: true, // allows curving of edges between nodes
                        type: "dynamic", // curvature of edges is associated with physics of the network when set to dynamic
                    },
                    color: {
                        color: "#C0C0C0",
                        highlight: '#275f9c',
                        hover: '#275f9c'
                    },
                    font: {
                        align: "middle",
                        size: 0
                    }
                }, // defined in the edges module.
                nodes: {
                    borderWidth: 5, // thickness of border around nodes
                    color: {
                        hover: {
                            background: '#89082f', // background color of node on hover
                            border: '#000000' // border color of node on hover
                        }
                    },
                    size: 20, // size of node
                    shapeProperties: {
                        useBorderWithImage: true
                    },
                    shape: "circularImage",
                }, // defined in the nodes module.

                interaction: {

                    hover: true,
                    tooltipDelay: 100
                }, // defined in the interaction module.
                // manipulation: {}, // defined in the manipulation module.
                physics: {
                    // stabilization: false,
                    barnesHut: {
                        gravitationalConstant: -15000, // setting repulsion (negative value) between the nodes
                        centralGravity: 0.5,
                        avoidOverlap: 0.5, // pulls entire network to the center
                        springLength: 95,
                        springConstant: 0.04,
                        damping: 0.09
                    },
                    stabilization: {
                        iterations: 2000,
                        updateInterval: 10,
                    }

                }, // defined in the physics module.
            };
            network = new LineageNetwork("my_network", default_options);
            network.mode = "normal"
            // lineage_network.mode="normal";
            var json_args = JSON.stringify({
                action: "filterSearchForALL"
            });
            console.log("Searching " + json_args);
            var nonFound = true;
            document.getElementById(network.conatiner_id).style.display = "none";
            var loading_img = document.getElementById("spin_loading");
            loading_img.style.display = 'inline-block';

            var start = Date.now();
            console.log(start);
            $.ajax({
                type: "POST",
                url: "./artistcontroller.php",
                data: json_args,
                success: function(response) {

                    console.log(response);
                    loading_img.style.display = 'none';
                    // document.getElementById(network.conatiner_id).style.display = "inline-block";
                    document.getElementById("loadingBar").style.display = "inline-block";

                    network.setDataFromArray(response["result"]);

                    network.drawIterations(10000);

                    network.vis_net.on("stabilizationProgress", function(params) {
                        // console.log(params.total);

                        // console.log(document.getElementById("loadingBar").style.display)
                        var maxWidth = 496;
                        var minWidth = 20;
                        var widthFactor = params.iterations / params.total;
                        var width = Math.max(minWidth, maxWidth * widthFactor);

                        document.getElementById("progress_bar").style.width = width + "px";
                        document.getElementById("progress_text").innerText =
                            Math.round(widthFactor * 100) + "%";
                    });

                    network.vis_net.on('stabilizationIterationsDone', function() {
                        let net = this;
                        setTimeout(function() {
                            document.getElementById("loadingBar").style.display = "none";
                            document.getElementById(network.conatiner_id).style.display = "inline-block";
                            net.stopSimulation();
                            net.fit();
                            console.log(Date.now() - start);
                        }, 100);
                    });
                },
                error: function(xhr, status, err) {
                    console.log(xhr.responseText);
                },
                dataType: "json",
                contentType: "application/json"
            });
        };
    </script>












    <?php
    include 'footer.php';
    ?>
    <style>
        #my_network {

            height: 1000px;

            width: 100%;
            border: 1px solid lightgray;
            /* background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), url("./img/logo.png"); */
            background-repeat: no-repeat;
            background-size: 100%;
            cursor: grab;
        }

        #network_container {
            height: 1000px;


            text-align: center;
        }

        div.outerBorder {
            position: relative;
            top: 400px;
            width: 600px;
            height: 60px;
            margin: auto auto auto auto;
            border: 8px solid rgba(0, 0, 0, 0.1);

            background: -moz-linear-gradient(top,
                    rgba(252, 252, 252, 1) 0%,
                    rgba(237, 237, 237, 1) 100%);
            /* FF3.6+ */
            background: -webkit-gradient(linear,
                    left top,
                    left bottom,
                    color-stop(0%, rgba(252, 252, 252, 1)),
                    color-stop(100%, rgba(237, 237, 237, 1)));
            /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,
                    rgba(252, 252, 252, 1) 0%,
                    rgba(237, 237, 237, 1) 100%);
            /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,
                    rgba(252, 252, 252, 1) 0%,
                    rgba(237, 237, 237, 1) 100%);
            /* Opera 11.10+ */
            background: -ms-linear-gradient(top,
                    rgba(252, 252, 252, 1) 0%,
                    rgba(237, 237, 237, 1) 100%);
            /* IE10+ */
            background: linear-gradient(to bottom,
                    rgba(252, 252, 252, 1) 0%,
                    rgba(237, 237, 237, 1) 100%);
            /* W3C */
            border-radius: 72px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        #progress_text {
            position: absolute;
            top: 8px;
            left: 530px;
            width: 30px;
            height: 50px;
            margin: auto auto auto auto;
            font-size: 22px;
            color: #000000;
        }

        #progress_border {
            position: absolute;
            top: 8px;
            left: 10px;
            width: 500px;
            height: 30px;
            box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        #progress_bar {
            position: absolute;
            top: 4px;
            left: 0px;
            width: 25px;
            height: 20px;
            border-radius: 11px;
            border: 2px solid rgba(30, 30, 30, 0.05);
            background: rgb(23, 219, 20);
            /* Old browsers */
            box-shadow: 2px 0px 4px rgba(0, 0, 0, 0.4);
        }

        #loadingBar {
            position: relative;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background-color: rgba(200, 200, 200, 0.8);
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -ms-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
            display: none;
            opacity: 1;
        }
    </style>
</body>
<html lang="en">