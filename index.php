<!DOCTYPE html> <!-- The new doctype -->
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>ColorPalette</title>

        <link rel="stylesheet" type="text/css" href="styles.css" />

        <!-- Internet Explorer HTML5 enabling code: -->

        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

        <style type="text/css">
        .clear {
          zoom: 1;
          display: block;
        }
        </style>


        <![endif]-->

    </head>

    <body>

    	<section id="page"> 

            <header>
                <hgroup>
                    <h1>Color Palette!</h1>
                    <h3>See all of the colors from a website!</h3>
                </hgroup>
            </header>

            <section id="articles"> 

			

                <div class="line"></div>  

                <article id="article1">
                    <h3>Choose:</h3>
                    <nav class="clear">
                      <ul>
                          <li><a href="#" id="p">Paste</a></li>
                          <li><a href="#" id="w">Web Address</a></li>
                      </ul>
                    </nav>
                    <div class="line"></div>

                    <div class="articleBody clear">
                       <section id="paste">
                         <p>Paste CSS here.</p>
                         <form name="paster" id="paster" method="post" enctype="multipart/form-data">
                         <textarea id="pasted" name="pasted" cols="40" rows="10"></textarea><br />
                         <input type="submit" value="Generate" />
                         </form>
                       </section>
                      <section id="web_address">
                        <p>Enter the url to a .css file.</p>
                        <form name="web" id="web" method="post" enctype="multipart/form-data">
                         <input type="text" name="weblink" id="weblink" /><br />
                         <input type="submit" value="Generate" />
                        </form>
                      </section>
                    </div>
                </article>
                <article id="palette"><div></div></article>
            </section>

        <footer> 

           <div class="line"></div>

        </footer>

		</section> 

        <!-- JavaScript Includes -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
        <script src="script.js"></script>
    </body>
</html>