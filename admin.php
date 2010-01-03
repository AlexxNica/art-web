<?php

/*
 * Copyright (C) 2010 Thomas Wood <thos@gnome.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require ('mysql.inc.php');
require ('config.inc.php');

session_start ();

if (array_key_exists ('logout', $_GET))
{
  session_unset ();
  unset ($_SESSION['granted']);
}

if (!array_key_exists ('granted', $_SESSION))
{
  $loginform =
  "<html><head><title>Login</title></head>
  <body><form method='post'>
  <label>User: <input name='username'></label>
  <label>Password: <input type='password' name='password'></label>
  <input type='submit' value='Login' name='login'></form></body></html>";

  if (array_key_exists ('login', $_POST))
  {
    /* attempt login */
    $username = mysql_real_escape_string ($_POST['username']);
    $password = $_POST['password'];
    $res = mysql_query ("SELECT password, level FROM user WHERE username = '$username'");
    $row = mysql_fetch_row ($res);
    if ($row[0] == md5 ($password) && $row[1] == 2)
      $_SESSION['granted'] = true;
    else
      exit ($loginform);
  }
  else
    exit ($loginform);
}


function print_combo ($name, $values, $selected)
{
  print ("<select name='$name'>");
  foreach ($values as $value => $name)
    printf ('<option value="%s"%s>%s</option>',
            $value, ($value == $selected) ? ' selected' : '', $name);
  print ("</select>");
}

if (array_key_exists ('section', $_GET))
  $section = mysql_real_escape_string ($_GET['section']);
else
  $section = '';

if (array_key_exists ('category', $_GET))
  $category = mysql_real_escape_string ($_GET['category']);
else
  $category = '';

if (array_key_exists ('edit', $_GET) && is_numeric ($_GET['edit']))
  $edit = $_GET['edit'];
else
  $edit = 0;
?>
<!DOCTYPE HTML SYSTEM>
<html>
<head>
  <title>Art-Web Admin</title>
  <style type="text/css">
    body { font-family: sans-serif; font-size: 10pt; padding-top: 1.5em;}
    table { font: inherit;}
    img { border: none; }
  </style>
</head>
<body>
<div style="position:fixed; background-color: black; color: white; top: 0; left: 0; right: 0; padding: 2px;">
Site Admin: <?php echo ucwords ($section); if ($category) echo " : " . ucwords (strtr ($category, '_', ' ')); ?>

<div style="float:right">
<a href="admin.php?logout" style="color:inherit">Logout</a>
</div>
</div>

<b>Themes:</b>
<a href="?section=theme&amp;category=gtk2">GTK</a>
&middot;
<a href="?section=theme&amp;category=metacity">Metacity</a>
&middot;
<a href="?section=theme&amp;category=icon">Icons</a>
&middot;
<a href="?section=theme&amp;category=splash_screens">Splash Screens</a>
&middot;
<a href="?section=theme&amp;category=gdm_greeter">Gdm Greeter</a>

<br>
<b>Backgrounds:</b>
<a href="?section=background&amp;category=gnome">GNOME</a>
&middot;
<a href="?section=background&amp;category=nature">Nature</a>
&middot;
<a href="?section=background&amp;category=abstract">Abstract</a>
&middot;
<a href="?section=background&amp;category=Other">Other</a>
<hr>
<?php

/* save an item */
if (array_key_exists ('save', $_POST))
{
  /* make sure we don't have any extra escaped characters, then do a real
   * escape */
  $_POST = array_map ('trim', $_POST);
  if (get_magic_quotes_gpc ())
    $_POST = array_map ('stripslashes', $_POST);
  $val = array_map ('mysql_real_escape_string', $_POST);

  if ($section == "theme")
    $sql = "UPDATE theme SET
            name='{$val["name"]}',
            status='{$val["status"]}',
            category='{$val["category"]}',
            version='{$val["version"]}',
            license='{$val["license"]}',
            parent={$val["parent"]},
            description='{$val["description"]}',
            preview_filename='{$val["preview_filename"]}',
            thumbnail_filename='{$val["thumbnail_filename"]}',
            download_filename='{$val["download_filename"]}'
            WHERE themeID={$val['themeID']} LIMIT 1";
  else if ($section == "background")
    $sql = "UPDATE background SET
            name='{$val["name"]}',
            status='{$val["status"]}',
            category='{$val["category"]}',
            version='{$val["version"]}',
            license='{$val["license"]}',
            description='{$val["description"]}',
            thumbnail_filename='{$val["thumbnail_filename"]}',
            parent='{$val["parent"]}'
            WHERE backgroundID={$val['backgroundID']} LIMIT 1";

  if (mysql_query ($sql))
    print ("<b>Saved</b>");
  else
    print ("<b>Save failed</b> " . mysql_error ());

  print ("<hr>");
}

if ($edit)
{
  /* edit page */
  if ($section == 'background')
    $sql = "SELECT backgroundID, name, status, category, version, license, parent, description,thumbnail_filename FROM background WHERE backgroundID=$edit";
  elseif ($section == 'theme')
    $sql = "SELECT themeID, name, status, category, version, license, parent, description, preview_filename, thumbnail_filename, download_filename FROM theme WHERE themeID=$edit";
  else
    exit ("No section selected");

  $res = mysql_query ($sql);
  print (mysql_error ());
  $row = mysql_fetch_assoc ($res);

  if ($section == 'theme')
    print ("<img alt='thumbnail' align=middle src='/images/thumbnails/{$row['category']}/{$row['thumbnail_filename']}'>");
  else
    print ("<img alt='thumbnail' align=middle src='/images/thumbnails/backgrounds/{$row['thumbnail_filename']}'>");
  print (' <b>'.$row['name'].'</b>');

  print ('<form method="post" action="'.$_SERVER['REQUEST_URI'].'"><table>');
  foreach ($row as $key => $value)
  {
    print ("\n<tr><td>".ucwords (strtr ($key, '_', ' '))."<td>");
    if ($key == 'status')
      print_combo ($key, array ('active' => 'Active', 'archive' => 'Archive', 'broken' => 'Broken', 'inactive' => 'Inactive'),
                   $value);
    elseif ($key == 'license')
      print_combo ($key, $license_config_array, $value);
    elseif ($key == 'category')
    {
      if ($section == 'background')
        print_combo ($key,
                     array_combine (array_keys ($background_config_array),
                                    array_keys ($background_config_array)),
                     $value);
      else
        print_combo ($key,
                     array_combine (array_keys ($theme_config_array),
                                    array_keys ($theme_config_array)),
                     $value);
    }
    else
      print ("<input name='$key' value='".htmlspecialchars ($value, ENT_QUOTES)."' size=50>");
  }
  print ('</table><input type="submit" name="save" value="Save"></form>');

  /* print background resolutions */
  if ($section == 'background')
  {
    print ("<b>Resolutions</b><ul>");
    $res = mysql_query ("SELECT * FROM background_resolution WHERE backgroundID=$edit");
    while ($row = mysql_fetch_assoc ($res))
    {
      print ("<li><a href=http://download.gnome.org/teams/art.gnome.org/backgrounds/{$row['filename']}>{$row['resolution']}</a>");
    }
    print ("</ul>");
  }
}
else
{
  /* listing page */

  if ($section)
    print ("<form method=get><input type=hidden name=section value='$section'>
            <input name=edit size=5><input type=submit value=Edit></form>");

  if ($section == 'theme')
    $sql = "SELECT themeID as artID, name, thumbnail_filename, status
            FROM theme WHERE category='$category' AND themeID > 1000";
  else
    $sql = "SELECT backgroundID as artID, name, thumbnail_filename, status
            FROM background WHERE category='$category' AND backgroundID > 1000";

  $res = mysql_query ($sql);
  print ('<table>');

  $col = 0;
  while ($row = mysql_fetch_assoc ($res))
  {
    if ($col == 0)
      print ("\n<tr>");
    if ($section == 'theme')
      print ("<td><img alt='thumbnail' src='/images/thumbnails/$category/{$row['thumbnail_filename']}'>");
    else
      print ("<td><img alt='thumbnail' src='/images/thumbnails/backgrounds/{$row['thumbnail_filename']}'>");

    print ("<td><a href='?edit={$row['artID']}&amp;section=$section'>{$row['name']}</a>");
    if ($row['status'] != 'active') print ("<br>({$row['status']})");

    $col++;
    if ($col > 2) $col = 0;
  }
  print ('</table>');
}

?>
</body>
</html>
