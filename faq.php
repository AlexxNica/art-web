<?php
include ("lib/template.php");


$t = new Template ("faq");
$t->print_header ();
?>

<div id="bottom">
  <div id="bottomLeft">
    <h2>FAQ</h2>
    <p>This is a collection of common queries and other useful bit of
    information about GNOME artwork.
    <h2>Licensing</h2>
    <p>The GNOME Desktop is licensed under the GPL, so it is only natural that
    the artwork in GNOME be licensed in keeping with same ideas of freedom.
    </p>
    <p>The GNOME icon theme itself is licensed under the GPL. A simple
    explenation of how this might apply to the artwork is avaible from Creative
    Commons <a href="http://creativecommons.org/licenses/GPL/2.0/">here</a>.

    <h3>Can I use a GNOME theme in my program or website?</h3>
    <p>Firstly, you must consult the license under which the artwork has been
    made available. If you are unsure of the license for a particular artwork,
    you must contact the author for confirmation.</p>
    <p>Having read the license, you must decide how it applies to your
    situation. Creative Commons have published some "human readable" versions of popular
    license, including:
    <ul>
    <li><a href="http://creativecommons.org/licenses/GPL/2.0/">GNU GPL</a></li>
    <li><a href="http://creativecommons.org/licenses/by-sa/2.0/">Creative
    Commons Attribution Share-Alike</a></li>
    </ul>
    </p>


    <h2>Using Themes</h2>
    <h3>Installation</h3>
    <p>Most desktop themes can usually be installed by using drag-and-drop on
    the appearance preferences window. The appearance preferences window is
    available on most systems by selecting the System menu, choosing Preferences,
    and clicking on the "Appearance" menu item.</p>
    <h3>Using Themes</h3>
    <p>GNOME provides the Appearance Preferences dialog to allow users to
    customise the appearance of their desktop. This can usually be found by
    choosing Preferences from the System menu, and clicking the "Appearance"
    menu item.</p>
    <p>From the Appearance Preferences, you can choose an entire new appearance,
    or you can customize individual aspects of the desktop. Selecting a preview
    from the Theme tab will automatically apply it to your current session. To
    customize specific aspects (such as Icons, Window borders, etc), click
    the Customise button. The Customize window then presents the various options
    available for specific aspects of GNOME.</p>
    <p>If you have created a custom group of settings, you can save this by
    selecting the "Save As..." button on the main Theme tab.</p>

    <h3>How do I use a theme-engine?</h3>

      <p>You will need to extract the archive you downloaded, then compile and install
      it. From the command line, change to the directory you extracted the files to,
      then run <tt>./configure --prefix &amp;&amp; make</tt>. After this has finished successfully,
      change to root (possibly by using <tt>su</tt> or <tt>sudo</tt>) and then
      run <tt>make install</tt>. You will need to make sure you have all the
      relevant development packages installed.</p>

      <p>If your distribution includes a copy of the theme engine you want to use, it
      is recommended you install it via your distribution instead.</p>
  </div>



  <div id="bottomRight">
    <h2>Contents</h2>
    <ul>
      <li><a href="#licensing">Licensing</a></li>
      <li><a href="#using-themes">Using GNOME Themes</a></li>
      <li><a href="#other">Other</a></li>
    </ul>
    </p>
  </div>


</div>


<?php $t->print_footer(); ?>
