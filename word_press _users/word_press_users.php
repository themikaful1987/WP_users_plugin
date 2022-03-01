<?php
/*
Plugin Name:  WP users
Plugin URI:
Description:  Description
Version:      1.0
Author:       Milan Cvetkovic
Author URI:
License:      GPL2
License URI:
Text Domain:   test
Domain Path:  /languages
*/


add_action('admin_menu', 'test_plugin_setup_menu');

function test_plugin_setup_menu()
{
    {
        add_menu_page('Plugin Page', 'WordPress Users', 'manage_options', 'test-plugin', 'test_init');
    }


    function test_init()
    {
        ?>
        <div class="wrap">
            <h2>Custom Options</h2>
            <form method="post" action="options.php">
                <?php wp_nonce_field('update-options') ?>
                <p><strong>Title:</strong><br/>
                    <input type="text" name="title" value="<?php echo get_option('title'); ?>"/>
                </p>

                <p><strong>Sub-Title:</strong><br/>
                    <input type="text" name="sub-title" value="<?php echo get_option('sub-title'); ?>"/>
                </p>

                <p><strong>Preview number:</strong><br/>
                    <input type="number" name="preview-number" value="<?php echo get_option('preview-number'); ?>"/>
                </p>
                <p><input type="submit" name="Submit" value="Save"/></p>
                <input type="hidden" name="action" value="update"/>
                <input type="hidden" name="page_options" value="title"/>
                <input type="hidden" name="page_options" value="sub-title"/>
                <input type="hidden" name="page_options" value="preview-number"/>
            </form>
        </div>
        <?php
    }
}

function users_table()
{
    echo "<h2>";
    echo get_option('title');
    echo "</h2>";
    echo "</br>";
    echo "<h3>";
    echo get_option('sub-title');
    echo "</h3>";
    echo "</br>";

    $prev_num = get_option('preview-number');

    if (current_user_can('administrator')): {
        $all_users = get_users();
        echo "<div class='custom_table'>
        <table id='myTable' border='1'>

        <tr>

        <th>Id</th>

        <th onclick='sortTable(0)'>Username</th>

        <th onclick='sortTable(1)'>Display Name</th>

        <th onclick='sortTable(0)'>Role</th>

        <th>Avatar</th>

        </tr>
       
        </div>";

        echo "<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById('myTable');
        switching = true;
  
        dir = 'asc'; 
  
        while (switching) {
    
        switching = false;
        rows = table.rows;
    
        for (i = 1; i < (rows.length - 1); i++) {
      
        shouldSwitch = false;
      
        x = rows[i].getElementsByTagName('TD')[n];
        y = rows[i + 1].getElementsByTagName('TD')[n];
      
        if (dir == 'asc') {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          
            shouldSwitch= true;
            break;
        }
         } else if (dir == 'desc') {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          shouldSwitch = true;
          break;
        }
      }
    }
        if (shouldSwitch) {
      
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
      
            switchcount ++;      
        } else {
         if (switchcount == 0 && dir == 'asc') {
        dir = 'desc';
        switching = true;
         }
         }
        }
    }
    </script>";
        if (empty($prev_num)) {
            foreach (array_slice($all_users, 0, 1000000) as $user) {
                echo '<tr>';
                echo '<td><span>' . esc_html($user->ID) . ' </span></td>';
                echo '<td><span>' . esc_html($user->user_login) . ' </span></td>';
                echo '<td><span>' . esc_html($user->display_name) . '</span></td>';
                echo '<td><span>' . implode(', ', $user->roles) . '</span></td>';
                echo '<td><span>' . get_avatar(get_the_author_meta('ID')) . '</span></td>';
                echo '</tr>';
            }
        } else {
            foreach (array_slice($all_users, 0, $prev_num) as $user) {
                echo '<tr>';
                echo '<td><span>' . esc_html($user->ID) . ' </span></td>';
                echo '<td><span>' . esc_html($user->user_login) . ' </span></td>';
                echo '<td><span>' . esc_html($user->display_name) . '</span></td>';
                echo '<td><span>' . implode(', ', $user->roles) . '</span></td>';
                echo '<td><span>' . get_avatar(get_the_author_meta('ID')) . '</span></td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    } else: {
        echo "Only Admin can see the List";
    }
    endif;

}

add_shortcode('users_table_preview', 'users_table');

function add_my_css(){
    wp_enqueue_style( 'your-stylesheet-name', plugins_url('/css/new-style.css', __FILE__), false, '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', "add_my_css");


?>