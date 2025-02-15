<?php
include 'util.php';
my_session_start();

include 'menu.php';

?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Dancestry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Asap">
    <link rel="stylesheet" href="css/global.css">
    <style>
      .portrait {
        width: 200px;
      }
      .align{
      font-stretch:normal;
      display: inline-block;
      float: left;
      line-break: auto;
      }
      table{
        text-align: left;
      }
    </style>
    <script type="text/javascript" >
      $(document).ready( function () {
        $("#about-event").css("background-color","#ddd");
      });
      function displayContentFunc(val, menuDiv){
        $(".abtDiv").css("background-color","F1F1F1");
        $(menuDiv).css("background-color","#ddd");
        $(".text-hide").hide();
        $(val).show();
      }
    </script>
  </head>

<div id="about_display_div" class="mrt10i">
    <div id="tab_bar_row" class="row tab" style=" margin: auto;">
        <button class="tablinks small-3 columns abtDiv" style="float: left;width: 25%;padding:5px;" id="about-event" onclick="displayContentFunc('#about-text','#about-event')">About
        </button>
        <button class="tablinks small-2 columns abtDiv" style="float: left;width: 25%;word-break:break-word;padding:5px;" id="pillar_event" onclick="displayContentFunc('#pillars-text', '#pillar_event')">Contributors
        </button>
        <button class="tablinks small-2 columns abtDiv" style="float: left;width: 25%;word-break:break-word;padding:5px;" id="contributor_event" onclick="displayContentFunc('#contributors-text', '#contributor_event')">Development Team
        </button>
        <button class="tablinks small-2 columns abtDiv" style="float: left;width: 25%; word-break:break-word;padding:5px;" id="library_event" onclick="displayContentFunc('#library-text', '#library_event')">Software/Libraries
        </button>
    </div>
</div>

  <body>

    <div class="row" style="padding-left: 10px; padding-right: 10px;">
      <div class="column text-justify">
        <section class="text-hide" id="about-text">
          <!-- <h2 >About</h2>
          <hr> -->
          <div class="row">
            <div class="column">
              <br/>
              Conceived and directed by Melanie Aceto, dancer, choreographer and associate professor at the University at Buffalo, Dancestry seeks to document, preserve and make accessible 20th and 21st century dance lineage in order to make possible the investigation of artistic influences, choreographic connections, career paths and myriad as of yet undetected relationships, associations and lineages.
              <br><br> Currently, dance lineage, who a dance artist studied with, danced for, collaborated with and was influenced by, is buried within books and documentaries that exist on only a few dance artists or in the memories of artists themselves.            
              <br><br>Melanie was interested in finding a way to document dance lineage and make it easily accessible. So…..
              <br><br>In the Spring of 2010 Melanie began working with Renee Ruffino, Creative Design Director for the University at Buffalo’s College of Arts and Sciences and Domenic Licata, Instructional Support Technician in the department of Visual Studies at the University at Buffalo (UB), to develop an illustration and mock-up of the idea of a dance lineage platform.
              <br><br>In May of 2011 Melanie hosted a focus group to see if this idea had merit. The focus group participants included Monica Bill Barnes, nationally recognized choreographer, Libby Smigel, Executive Director, Dance Heritage Coalition, Sara Schwabacher, American Dance Legacy Institute, Maura Keefe, Jacob’s Pillow Dance Scholar, Domenic Licata, Instructional Technology Consultant, Jeff Good, UB Assistant Professor of Linguistics, Laura Neese, UB dance major, Mark Ludwig, UB Libraries Systems Manager, and Renee Ruffino, Graphic Designer. Melanie was encouraged to move forward with this idea and began building.
              <br><br>Artists initially shared their lineage through an excel form and Gephi was adopted as a visualization software. In 2013 Dr. Bina Ramamurthy, a computer scientist at UB joined the project. Through internal funding from the University at Buffalo throughout the 2013/2014 and 2014/2015 academic years, Aceto, Ramamurthy and a team of UB computer science graduate students developed a working prototype.  The prototype included a lineage data collection form on formassembly(.com) (commercial software) that was stored in a simple text file and had the ability to make primary visualizations utilizing Gephi software, based on a modest sample of a little more than 30 artists’ lineage. 
              <br><br>The project continued to be developed by graduate student teams over the next few years.  The focus was on creating a stable lineage (data) contribution form, a website to house it, and a searchable database. In an effort to be able to search for date related connections between artists, the lineage contribution form initially included date data for each relationship; the year/month and duration of the lineal relationship. However, creating this level of detail with ever-changing student teams proved too challenging, so we shifted to focusing just on the who, not the when. 
              <br><br>In September of 2018 the dance lineage project joined the Invent/Invest Network at UB (https://invenst.cse.buffalo.edu/). Professor Alan Hunt in UB Computer Science and Engineering began working as project manager, closely mentoring the graduate student teams. Through his mentorship, the lineage contribution form, website and lineage visualization have been developed into a working platform. Currently the tech stack is PHP, the database is MySQL and the data visualization is vis.js.
              <br><br>Part social network and part archive, Dancestry collects, preserves, analyzes, and makes accessible dance lineage: who dancers have studied with, danced in the work of, collaborated with and have been influenced by. Dancestry is intended as a global resource for investigating artistic influences, career paths, choreographic connections, and complex and obscure relationships. A broader goal of the project is to develop a template that can be utilized in other disciplines, from music to physics, to share their own lineage.
              <br><br><strong>Intended Applications:</strong>
              <strong>Dance scholars</strong> can trace the migration of dance techniques and styles from teacher to student and from choreographer to dancer. <strong>Dance researchers </strong>will be able to access a choreographer’s teachers as well as students they taught revealing artistic connections in both directions. Dancestry illuminates shared influences. The work of several choreographers may have similarities due to studying with or dancing for the same teacher or choreographer, and Dancestry would reveal this connection.<strong> Dance students</strong> unable to study with an artist abroad can find an artist nationally to study with, who danced for that abroad choreographer and has a similar style. <strong> Dance audiences </strong>can easily educate themselves by investigating connections among the work they like. <strong> Dance educators</strong> can further their study of a particular dance form by seeking out teachers who have danced for and studied with artists in that lineage. 
              <br><br>
              Dancestry not only includes information about dancers, but the composers, designers, writers and artists with whom they have collaborated and therefore is intrinsically connected to contemporaneous movements in these other disciplines. The evolution of dance is not isolated from, but integral and parallel to the evolution of the other arts.   
              <br><br><strong>Hopes for the Future:</strong>
              An understanding of where dance came from is vital for the evolution of the art form. Dancestry is a resource, an archive, a history book, a directory, and a network. Dancestry provides an ever-expanding resource for the dance field and a growing historical document as future generations of dancers contribute their lineage.             </div>
          </div><br>
        </section>
        <section class="text-hide" id="pillars-text" style="display:none;padding:10px;">
          <!-- <h2>Contributors</h2> -->
          <!-- <hr> -->
          <br/>
          <div class="row">
            <div class="medium-3 column text-center"><p><img src="data/images/about/aceto2.jpg" alt="" class="portrait"></p></div>
            <div class="medium-9 column"><strong>Director </strong> <br>Melanie Aceto is a dancer, choreographer and educator whose creative interests are in interdisciplinary solo and large group works. Melanie’s research interests include investigating models for teaching dance composition and movement practices and creating resources for both. She earned her MFA in Dance at New York University’s Tisch School of the Arts and is currently an Associate Professor of Dance at the University at Buffalo.(<a href="http://www.melanieaceto.com/">www.melanieaceto.com</a>)</div>
      
          </div>
          <hr>
          <div class="row">
            <div class="medium-3 column text-center"><p><img src="data/images/about/alan.jpg" alt="" class="portrait"></p></div>
            <div class="medium-9 column align"><strong>Project Manager</strong>
            Alan Hunt is a Professor of Practice in the Computer Science and Engineering department at the University at Buffalo. He comes to the department from a 20-year career in software development, leading global teams in application development and innovation. He has a passion for technology and mentoring the next generation of innovators.
          </div>
		      <hr>
          <!-- <div class="row">
            <div class="medium-3 column text-center"><p><img src="data/images/about/bina.png" alt="" class="portrait"></p></div>
            <div class="medium-9 column"><strong>Data Scientist</strong> Dr. Bina Ramamurthy is a faculty at University at Buffalo, Computer Science and Engineering Department. She has been involved in the STEM area research, curriculum development and instruction for the past two decades. Her current research is in data-intensive computing with emphasis on cloud infrastructures. She is the Principal Investigator on four National Science Foundation (NSF) grants and a co-investigator in four Instructional Innovative Instructional Technology grants (IITG) from SUNY. She has given numerous invited presentations at prominent conferences in the areas of data-intensive and big-data computing. She has been on the program committees of prestigious conferences including the High Performance Computing Conference (HPCIC2010), India and Special Interest Group in Computer Science Education (SIGCSE). Bina Ramamurthy received the B.E. (Honors) in Electronics and Communication from Guindy Engineering College, Madras University, India, the M.S. in Computer Science from Wichita State University, KS, and the Ph.D. in Electrical Engineering (1997) from the University at Buffalo, Buffalo, NY. She is a member of ACM and IEEE Computer Society.</div>
          </div> -->
          <!-- <hr>
          <div class="row">
            <div class="medium-3 column text-center"><p><img src="data/images/about/licata.png" alt="" class="portrait" style="border-radius: 10px;"></p></div>
            <div class="medium-9 column"><strong>User Experience Designer</strong> Domenic J. Licata is an Instructional Support Technician and Instructor with the Department of Art at the University at Buffalo. His primary areas of focus are Graphic Design and Emerging Practices. He received an M.Ed. in Education and Technology from UB in 2008. Domenic maintains technologies which foster art- and design-making collaboration and constructive learning experiences. He teaches several courses which he has developed, including Web Design, Introduction to Digital Practices, and 2D Animation. He is interested in finding meaning through visual rhetoric and semiotics within postmodern culture, searching for ways in which cultural practices mesh with teaching and learning theories of social constructivism. Domenic's research has explored how the creation and consumption of digital media effects cognitive development, and how the design student can be guided in the age of the social networks to become a creative and critical thinker.</div>
          </div>
          <hr> -->
          <div class="row">
            <div class="medium-3 column text-center"><p><img src="data/images/about/ruffino.png" alt="" class="portrait"></p></div>
            <div class="medium-9 column">
              
            <strong>Creative Director</strong>
            Renee Ruffino is both designer and artist. As the Creative Director for the College of Arts & Sciences at the University at Buffalo and an Adjunct Professor in the Graphic Design concentration in the Department of Art at UB for over 23 years, Renee taught typography, identity design and printing processes courses. Renee's use of type and photography explore what it means to be marginalized by the dominant culture, placing an emphasis on women and gender. 
          </div>
        </section>
        <section class="text-hide" id="contributors-text" style="display:none">
        <p id="contributors">
          <h4>Development Team</h4>
          <div style="column-count: 4;" >
          <div>
            <ul>
              <li>Abhishek Bhave</li>
              <li>Adityan Harikrishnan</li>
              <li>Bharath Gangishetti</li>
              <li>Charul Dadhich</li>
              <li>Clara Cook</li>
              <li>Elizabeth Kaltenbach</li>
              <li>Girija Polamreddy</li>
              <li>Gokul Sai Kadaparthi</li>
              <li>Gopi Chand Pendyala</li>
              <li>Hanzhang Bai</li>
              <li>Harinee Purushothaman</li>
              <li>Jay Shah</li>
              <li>Jeevan Sagar Batana</li>
              <li>Laura Neese</li>
              <li>Mangesh Vilas Kaslikar</li>
              <li>Michael Rogers</li>
              <li>Misha Chekhov</li>
              <li>Naila Ansari</li>
              <li>Omkar Thorat</li>
              <li>Phuoc Ky Anh Tran</li>
              <li>Sai Cao</li>
              <li>Sai Neeraj Sabbisetti</li>
              <li>Sarah Dodd</li>
              <li>Sarang Agarwal</li>
              <li>Sendil Balan Palanivel</li>
              <li>Shailesh Adhikari</li>
              <li>Shivam Sahu</li>
              <li>Srikar Panuganti</li>
              <li>Srinivas Chaitanya Cheepuri</li>
              <li>Sumanth Reddy Adupala</li>
              <li>Sumedh Ambokar</li>
              <li>Tejasri Karuturi</li>
              <li>Tianyu Cao</li>
              <li>Vaidehi Dharkar</li>
              <li>Venkata Sai Tarun Kodavati</li>
              <li>Wren Martinson</li>
              <li>Yash Jain</li>
              <li>Yash Nitin Mantri</li>
              <li>Yunfei Hou</li>
              <li>Zeping Wang</li>
            </ul>
          </div>

        </section>
        <section class="text-hide" id="library-text" style="display:none">
        <p id="osslicense">

        <h4>Open Source Software/Libraries in Dancestry</h4> 
        
        <table>
        <tr>
        <th>vis.js under MIT Licence</th>
        <th><a href="https://github.com/visjs/vis-network/" target="_blank">Visit vis.js repository!</a></th>
        </tr>
        <tr>
        <th>platform.js under MIT Licence</th>
        <th><a href="https://github.com/bestiejs/platform.js/" target="_blank">Visit platform.js repository!</a></th>
        </tr>
        </table>
        </section>
        

      </div>
    </div>

  </body>
  <?php
include 'footer.php';
?>
</html>
