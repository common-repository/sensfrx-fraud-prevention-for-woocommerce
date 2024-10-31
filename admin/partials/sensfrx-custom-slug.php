<?php 
/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 * 
 * @link       https://sensfrx.ai
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/admin/partials
 */

if (!function_exists('add_action')) { 
	die(); 
}
if (!defined('ABSPATH')) {
	die('ASPATH is required - Custome Data PAGE');
}
global $wpdb;
$custom_field_succees = null;
$custom_field_error = null;
$custom_field_update = null;
$custom_field_update_error = null;
$custom_field_delete = null;
$custom_field_delete_error = null;
$custom_field_update_custom = null;
$custom_field_update_custom_error = null;
$custom_field_custom_delete = null;
$custom_field_custom_delete_error = null;

if ( isset($_POST['custom_sensfrx_tab_&_slug_button']) && isset($_POST['sensfrx_custom_tab_slug_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_tab_slug_nonce']), 'sensfrx_custom_tab_slug_action') ) {
    $sensfrx_data_tab = $_POST['custom_sensfrx_tab_&_slug'];
    $sensfrx_data_slug = $_POST['custom_sensfrx_tab_&_slug_one'];
    if (null !== $sensfrx_data_tab && '' !== $sensfrx_data_tab) {
        $table_name = $wpdb->prefix . 'sensfrx_tab_data'; 
        // Prepare data for insertion
        $data = array(
            'sensfrx_tab' => $sensfrx_data_tab,
        );
        // Insert data into the custom table
        $results = wp_cache_get('sensfrx_custom_data_using', 'sensfrx_group');
        if ($results !== false) {
            $results = $wpdb->insert($table_name, $data);
            wp_cache_replace('sensfrx_custom_data_using', $results, 'sensfrx_group', 86400);
        } else {
            $results = $wpdb->insert($table_name, $data);
            wp_cache_set('sensfrx_custom_data_using', $results, 'sensfrx_group', 86400);
            if (false === $results) {
                $custom_field_error = true;
            } else {
                $custom_field_succees = true;
            }
        }
    }
    if (null !== $sensfrx_data_slug && '' !== $sensfrx_data_slug) {
        $table_name = $wpdb->prefix . 'sensfrx_custome_filed'; 
        // Prepare data for insertion
        $data = array(
            'sensfrx_custom_field' => $sensfrx_data_slug,
        );
        // Insert data into the custom table
        $results = wp_cache_get('sensfrx_custom_data_using', 'sensfrx_group');
        if ($results !== false) {
            $results = $wpdb->insert($table_name, $data);
            wp_cache_replace('sensfrx_custom_data_using', $results, 'sensfrx_group', 86400);
        } else {
            $results = $wpdb->insert($table_name, $data);
            wp_cache_set('sensfrx_custom_data_using', $results, 'sensfrx_group', 86400);
            if (false === $results) {
                $custom_field_error = true;
            } else {
                $custom_field_succees = true;
            }
        }
    }
}
$table_name = $wpdb->prefix . 'sensfrx_tab_data';
$table_name_custom = $wpdb->prefix . 'sensfrx_custome_filed';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['custom_sensfrx_tab_&_slug_delete_update'])) {
        // Handle Delete Action
        $id = intval($_POST['custom_sensfrx_tab_&_slug_one_delete_id_1']);
        $deleted = $wpdb->delete($table_name, ['id' => $id]);

        if ($deleted) {
            $custom_field_delete = true;
        } else {
            $custom_field_delete_error = true;
        }
    } elseif (isset($_POST['custom_sensfrx_tab_&_slug_button_update']) && isset($_POST['sensfrx_custom_tab_slug_nonce_update']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_tab_slug_nonce_update']), 'sensfrx_custom_tab_slug_action_update')) {
        // Handle Update Action
        $id = intval($_POST['custom_sensfrx_tab_&_slug_one_update_id_1']);
        $new_name = sanitize_text_field($_POST['custom_sensfrx_tab_&_slug_one_update']);

        $updated = $wpdb->update(
            $table_name,
            ['sensfrx_tab' => $new_name], // Update column
            ['id' => $id]                 // Where clause
        );

        if (false !== $updated) {
            $custom_field_update = true;
        } else {
            $custom_field_update_error = true;
        }
    }

    if (isset($_POST['custom_custom_sensfrx_tab_&_slug_delete_update'])) {
        // Handle Delete Action
        $id = intval($_POST['custom_custom_sensfrx_tab_&_slug_one_delete_id_1']);
        $deleted = $wpdb->delete($table_name_custom, ['id' => $id]);

        if ($deleted) {
            $custom_field_custom_delete = true;
        } else {
            $custom_field_custom_delete_error = true;
        }
    } elseif (isset($_POST['custom_custom_sensfrx_tab_&_slug_button_update']) && isset($_POST['sensfrx_custom_custom_tab_slug_nonce_update']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_custom_custom_tab_slug_nonce_update']), 'sensfrx_custom_custom_tab_slug_action_update')) {
        // Handle Update Action
        $id = intval($_POST['custom_custom_sensfrx_tab_&_slug_one_update_id_1']);
        $new_name = sanitize_text_field($_POST['custom_custom_sensfrx_tab_&_slug_one_update']);

        $updated = $wpdb->update(
            $table_name_custom,
            ['sensfrx_custom_field' => $new_name], // Update column
            ['id' => $id]                 // Where clause
        );

        if (false !== $updated) {
            $custom_field_update_custom = true;
        } else {
            $custom_field_update_custom_error = true;
        }
    }
}
$table_name = $wpdb->prefix . 'sensfrx_tab_data';
$sensfrx_fetch_results = $wpdb->get_results("SELECT id, sensfrx_tab FROM $table_name", ARRAY_A);

$table_name_custom = $wpdb->prefix . 'sensfrx_custome_filed';
$sensfrx_fetch_results_custom = $wpdb->get_results("SELECT id, sensfrx_custom_field FROM $table_name_custom", ARRAY_A);
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag and Custom Field Form</title>
    <style>

        .sensfrx-custom-slug-form-container {
            background-color: #f9f9f9;
            margin: 0 auto;
            /* background: #fff; */
            padding: 7px 15px 15px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sensfrx-custom-slug-form-group {
            margin-bottom: 15px;
        }

        .sensfrx-custom-slug-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .sensfrx-custom-slug-form-group input {
            /* width: calc(100% - 20px); */
            width: 50%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .sensfrx-custom-slug-instruction {
            font-size: 12px;
            color: #555;
            margin-top: 5px;
        }

        .sensfrx-custom-slug-save-button {
            background-color: #4169e1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 120px;
            margin-top: 10px;
        }

        .sensfrx-custom-slug-save-button:hover {
            background: #218838;
        }

        .sensfrx_tab_slug_table-container {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        #sensfrx_tab_slug_table_represention {
            width: 100%;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-spacing: 0;
        }

        .sensfrx_tab_slug_table_th, 
        .sensfrx_tab_slug_table_td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .sensfrx_tab_slug_table_th {
            background-color: #f2f2f2;
        }

        .sensfrx_tab_slug_table_tr:hover {
            background-color: #f9f9f9;
        }

        #sensfrx_tab_slug_table_button {
            padding: 8px 15px; /* Adjust padding for a balanced look */
            margin-right: 5px;
            border: 1px solid #0073aa; /* Light border with a professional color */
            border-radius: 4px; /* Rounded corners for a smooth appearance */
            color: #fff; /* White text for contrast */
            font-size: 14px; /* Appropriate font size for readability */
            font-weight: 500; /* Slightly bold text for emphasis */
            cursor: pointer;
            transition: all 0.3s ease; /* Smooth transition for hover effects */
        }

        .sensfrx_tab_slug_update-btn {
            background-color: #4CAF50;
            padding: 8px 15px; /* Adjust padding for a balanced look */
            margin-right: 5px;
            border: 1px solid #0073aa; /* Light border with a professional color */
            border-radius: 4px; /* Rounded corners for a smooth appearance */
            color: #fff; /* White text for contrast */
            font-size: 14px; /* Appropriate font size for readability */
            font-weight: 500; /* Slightly bold text for emphasis */
            cursor: pointer;
            transition: all 0.3s ease; /* Smooth transition for hover effects */
            /* color: white; */
        }

        .sensfrx_tab_slug_delete-btn {
            background-color: #f44336;
            color: white;
        }

        /* .success {
            color: green;
            margin: 10px 0;
        }

        .error {
            color: red;
            margin: 10px 0;
        } */

        .sensfrx_tab_slug_no-data {
            text-align: center;
            padding: 20px;
            font-size: 16px;
            color: #666;
        }

        .sensfrx_tab_slug_table-heading {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<?php 
if (true === $custom_field_succees) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Custom Data Added Successfully...
	</div>
	<?php 
} 
if (true === $custom_field_error) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Custom Data Not Added Successfully...
	</div>
	<?php 
}
if (true === $custom_field_update) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Custom Tag Updated Successfully...
	</div>
	<?php 
} 
if (true === $custom_field_update_error) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Custom Tag Not Updated Successfully...
	</div>
	<?php 
}
if (true === $custom_field_delete) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Custom Tag Deleted Successfully...
	</div>
	<?php 
} 
if (true === $custom_field_delete_error) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Custom Tag Not Deleted Successfully...
	</div>
	<?php 
}
if (true === $custom_field_update_custom) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Custom Field Updated Successfully...
	</div>
	<?php 
} 
if (true === $custom_field_update_custom_error) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Custom Field Not Updated Successfully...
	</div>
	<?php 
}
if (true === $custom_field_custom_delete) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Custom Field Deleted Successfully...
	</div>
	<?php 
} 
if (true === $custom_field_custom_delete_error) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Custom Field Not Deleted Successfully...
	</div>
	<?php 
}
?>  
    <form id="form-11" action="#sensfrx_custom_slug" method="post">
		<?php wp_nonce_field('sensfrx_custom_tab_slug_action', 'sensfrx_custom_tab_slug_nonce'); ?>
		<input type="hidden" name="active_tab" value="tab-8">
		<input type="hidden" name="input_1_tab3" placeholder="Input 1">
		<input type="hidden" name="input_2_tab3" placeholder="Input 2">
		<input type="hidden" name="custom_sensfrx_tab_&_slug" id="custom_sensfrx_tab_&_slug" >
        <input type="hidden" name="custom_sensfrx_tab_&_slug_one" id="custom_sensfrx_tab_&_slug_one" >
		<input type="submit" name="custom_sensfrx_tab_&_slug_button" class="sensfrx_hide_button" id="custom_sensfrx_tab_&_slug_button" value="Submit">
	</form>
    <form id="form-11" action="#sensfrx_custom_slug" method="post">
		<?php wp_nonce_field('sensfrx_custom_tab_slug_action_update', 'sensfrx_custom_tab_slug_nonce_update'); ?>
		<input type="hidden" name="active_tab" value="tab-8">
		<input type="hidden" name="input_1_tab3" placeholder="Input 1">
		<input type="hidden" name="input_2_tab3" placeholder="Input 2">
		<input type="hidden" name="custom_sensfrx_tab_&_slug_one_update_id_1" id="custom_sensfrx_tab_&_slug_one_update_id_1" >
        <input type="hidden" name="custom_sensfrx_tab_&_slug_one_update" id="custom_sensfrx_tab_&_slug_one_update" >
		<input type="submit" name="custom_sensfrx_tab_&_slug_button_update" class="sensfrx_hide_button" id="custom_sensfrx_tab_&_slug_button_update" value="Submit">
	</form>
    <form id="form-11" action="#sensfrx_custom_slug" method="post">
		<?php wp_nonce_field('sensfrx_custom_tab_slug_action_update', 'sensfrx_custom_tab_slug_nonce_update'); ?>
		<input type="hidden" name="active_tab" value="tab-8">
		<input type="hidden" name="input_1_tab3" placeholder="Input 1">
		<input type="hidden" name="input_2_tab3" placeholder="Input 2">
		<input type="hidden" name="custom_sensfrx_tab_&_slug_one_delete_id_1" id="custom_sensfrx_tab_&_slug_one_delete_id_1" >
        <input type="submit" name="custom_sensfrx_tab_&_slug_delete_update" class="sensfrx_hide_button" id="custom_sensfrx_tab_&_slug_delete_update" value="Submit">
	</form>
    <form id="form-11" action="#sensfrx_custom_slug" method="post">
		<?php wp_nonce_field('sensfrx_custom_custom_tab_slug_action_update', 'sensfrx_custom_custom_tab_slug_nonce_update'); ?>
		<input type="hidden" name="active_tab" value="tab-8">
		<input type="hidden" name="input_1_tab3" placeholder="Input 1">
		<input type="hidden" name="input_2_tab3" placeholder="Input 2">
		<input type="hidden" name="custom_custom_sensfrx_tab_&_slug_one_update_id_1" id="custom_custom_sensfrx_tab_&_slug_one_update_id_1" >
        <input type="hidden" name="custom_custom_sensfrx_tab_&_slug_one_update" id="custom_custom_sensfrx_tab_&_slug_one_update" >
		<input type="submit" name="custom_custom_sensfrx_tab_&_slug_button_update" class="sensfrx_hide_button" id="custom_custom_sensfrx_tab_&_slug_button_update" value="Submit">
	</form>
    <form id="form-11" action="#sensfrx_custom_slug" method="post">
		<?php wp_nonce_field('sensfrx_custom_tab_slug_action_update', 'sensfrx_custom_tab_slug_nonce_update'); ?>
		<input type="hidden" name="active_tab" value="tab-8">
		<input type="hidden" name="input_1_tab3" placeholder="Input 1">
		<input type="hidden" name="input_2_tab3" placeholder="Input 2">
		<input type="hidden" name="custom_custom_sensfrx_tab_&_slug_one_delete_id_1" id="custom_custom_sensfrx_tab_&_slug_one_delete_id_1" >
        <input type="submit" name="custom_custom_sensfrx_tab_&_slug_delete_update" class="sensfrx_hide_button" id="custom_custom_sensfrx_tab_&_slug_delete_update" value="Submit">
	</form>
    <div class="sensfrx-custom-slug-form-container">
        <h2 class="sensfrx_tab_heading">Custom Data :</h2>
        <form action="" method="post">
            <div class="sensfrx-custom-slug-form-group">
                <label for="tag_name">Tag Name:</label>
                <input type="text" id="sensfrx_custom_field_tab" name="tag_name" required>
                <p class="sensfrx-custom-slug-instruction">Enter the name of the product tag.</p>
            </div>
            <input type="submit" name="sensfrx-custom-tag" onclick="sensfrx_custom_tab()" class="sensfrx-custom-slug-save-button">
        </form>
        <br>
        <form action="" method="post">
            <div class="sensfrx-custom-slug-form-group">
                <label for="custom_field">Custom Field Data:</label>
                <input type="text" id="sensfrx_custom_field_slug" name="custom_field" required>
                <p class="sensfrx-custom-slug-instruction">Enter the relevant custom field data here.</p>
            </div>
            <input type="submit" name="sensfrx-custom-slug" onclick="sensfrx_custom_slug()" class="sensfrx-custom-slug-save-button">
        </form>
    </div>
    <br>
    <div class="sensfrx_tab_slug_table-container">
        <h2 class="sensfrx_tab_slug_table-heading">Provided Tag</h2>
        <?php if (!empty($sensfrx_fetch_results)): ?>
            <form method="post">
                <table id="sensfrx_tab_slug_table_represention">
                    <thead>
                    <tr class=".sensfrx_tab_slug_table_tr">
                        <th class="sensfrx_tab_slug_table_th">Name</th>
                        <th class="sensfrx_tab_slug_table_th">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sensfrx_fetch_results as $row): ?>
                        <tr class=".sensfrx_tab_slug_table_tr">
                            <td class="sensfrx_tab_slug_table_td">
                            <input 
                                type="text" 
                                name="sensfrx_custom_field_tab_update_<?php echo esc_attr($row['id']); ?>" 
                                id="sensfrx_custom_field_tab_update_<?php echo esc_attr($row['id']); ?>" 
                                value="<?php echo esc_attr($row['sensfrx_tab']); ?>" 
                                data-id="<?php echo esc_attr($row['id']); ?>">
                                <!-- Hidden field to pass the row ID -->
                                <input type="hidden" name="custom_sensfrx_tab_&_slug_one_update_id" id="custom_sensfrx_tab_&_slug_one_update_id" value="<?php echo esc_attr($row['id']); ?>">
                            </td>
                            <td class="sensfrx_tab_slug_table_th">
                                <!-- Update Button -->
                                <!-- <button class="sensfrx_tab_slug_update-btn" id="sensfrx_tab_slug_table_button_update" type="submit" name="sensfrx_tab_slug_update" value="<?php echo esc_attr($row['id']); ?>" onclick="sensfrx_custom_tab_update(<?php echo esc_attr($row['id']); ?>)">Update</button> -->
                                <!-- Delete Button -->
                                <button id="sensfrx_tab_slug_table_button" type="submit" name="sensfrx_tab_slug_delete" class="sensfrx_tab_slug_delete-btn" onclick="sensfrx_custom_tab_delete(<?php echo esc_attr($row['id']); ?>)">Delete</button>
                                <!-- Hidden field to pass the row ID -->
                                <!-- <input type="text" name="custom_sensfrx_tab_&_slug_one_update_id" id="custom_sensfrx_tab_&_slug_one_update_id" value="<?php echo esc_attr($row['id']); ?>"> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p class="sensfrx_tab_slug_no-data">No data found.</p>
        <?php endif; ?>
    </div>

    <br>
    <div class="sensfrx_tab_slug_table-container">
        <h2 class="sensfrx_tab_slug_table-heading">Provided Custom Field</h2>
        <?php if (!empty($sensfrx_fetch_results_custom)): ?>
            <form method="post">
                <table id="sensfrx_tab_slug_table_represention">
                    <thead>
                    <tr class=".sensfrx_tab_slug_table_tr">
                        <th class="sensfrx_tab_slug_table_th">Name</th>
                        <th class="sensfrx_tab_slug_table_th">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sensfrx_fetch_results_custom as $row): ?>
                        <tr class=".sensfrx_tab_slug_table_tr">
                            <td class="sensfrx_tab_slug_table_td">
                            <input 
                                type="text" 
                                name="sensfrx_custom_field_tab_update_<?php echo esc_attr($row['id']); ?>" 
                                id="sensfrx_custom_custom_field_tab_update_<?php echo esc_attr($row['id']); ?>" 
                                value="<?php echo esc_attr($row['sensfrx_custom_field']); ?>" 
                                data-id="<?php echo esc_attr($row['id']); ?>">
                                <!-- Hidden field to pass the row ID -->
                                <input type="hidden" name="custom_sensfrx_tab_&_slug_one_update_id" id="custom_sensfrx_tab_&_slug_one_update_id" value="<?php echo esc_attr($row['id']); ?>">
                            </td>
                            <td class="sensfrx_tab_slug_table_th">
                                <!-- Update Button -->
                                <!-- <button class="sensfrx_tab_slug_update-btn" id="sensfrx_tab_slug_table_button_update" type="submit" name="sensfrx_tab_slug_update" value="<?php echo esc_attr($row['id']); ?>" onclick="sensfrx_custom_custom_tab_update(<?php echo esc_attr($row['id']); ?>)">Update</button> -->
                                <!-- Delete Button -->
                                <button id="sensfrx_tab_slug_table_button" type="submit" name="sensfrx_tab_slug_delete" class="sensfrx_tab_slug_delete-btn" onclick="sensfrx_custom_custom_tab_delete(<?php echo esc_attr($row['id']); ?>)">Delete</button>
                                <!-- Hidden field to pass the row ID -->
                                <!-- <input type="text" name="custom_sensfrx_tab_&_slug_one_update_id" id="custom_sensfrx_tab_&_slug_one_update_id" value="<?php echo esc_attr($row['id']); ?>"> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p class="sensfrx_tab_slug_no-data">No data found.</p>
        <?php endif; ?>
    </div>

</body>
<script>

    function sensfrx_custom_tab() {
        var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        if (button) {
            button.click();
        } else {
            console.error('Save Change Button not found');
        }

        const custom_sensfrx_tab = document.getElementById('sensfrx_custom_field_tab').value;
        // console.log(custom_sensfrx_tab);

        if (custom_sensfrx_tab !== null && custom_sensfrx_tab !== '') {
            const table_sensfrx_input = document.getElementById('custom_sensfrx_tab_&_slug');
            table_sensfrx_input.value = custom_sensfrx_tab;
            // console.log(table_sensfrx_input.value);
            setTimeout(function() {
                document.getElementById("custom_sensfrx_tab_&_slug_button").click();
            }, 100); 
        }
    }

    function sensfrx_custom_slug() {
        var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        if (button) {
            button.click();
        } else {
            console.error('Save Change Button not found');
        }

        const custom_sensfrx_slug = document.getElementById('sensfrx_custom_field_slug').value;
        // console.log(custom_sensfrx_slug);

        if (custom_sensfrx_slug !== null && custom_sensfrx_slug !== '') {
            const table_sensfrx_input = document.getElementById('custom_sensfrx_tab_&_slug_one');
            table_sensfrx_input.value = custom_sensfrx_slug;
            // console.log(table_sensfrx_input.value);
            setTimeout(function() {
                document.getElementById("custom_sensfrx_tab_&_slug_button").click();
            }, 100); 
        }
    }

    function sensfrx_custom_tab_update(id) {
        var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        if (button) {
            button.click();
        } else {
            console.error('Save Change Button not found');
        }

        if(id !== null && id !== undefined) {

            const updated_name = document.querySelector(`#sensfrx_custom_field_tab_update_${id}`);
            const updatedValue = updated_name.value;
            // console.log(updatedValue);
            // console.log(id)

            const table_sensfrx_input_id = document.getElementById('custom_sensfrx_tab_&_slug_one_update_id_1');
            table_sensfrx_input_id.value = id;
            const table_sensfrx_input_updated_name = document.getElementById('custom_sensfrx_tab_&_slug_one_update');
            table_sensfrx_input_updated_name.value = updatedValue;
            setTimeout(function() {
                document.getElementById("custom_sensfrx_tab_&_slug_button_update").click();
            }, 100); 
            
        }
    }

    function sensfrx_custom_tab_delete(sensfrx_delete_id){
        var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        if (button) {
            button.click();
        } else {
            console.error('Save Change Button not found');
        }

        if(sensfrx_delete_id !== null && sensfrx_delete_id !== undefined) {
            // console.log(sensfrx_delete_id);
            const table_sensfrx_input_updated_name = document.getElementById('custom_sensfrx_tab_&_slug_one_delete_id_1');
            table_sensfrx_input_updated_name.value = sensfrx_delete_id;
            setTimeout(function() {
                document.getElementById("custom_sensfrx_tab_&_slug_delete_update").click();
            }, 100); 
        }
    }

    function sensfrx_custom_custom_tab_update(id) {
        var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        if (button) {
            button.click();
        } else {
            console.error('Save Change Button not found');
        }

        if(id !== null && id !== undefined) {

            const updated_name = document.querySelector(`#sensfrx_custom_custom_field_tab_update_${id}`);
            const updatedValue = updated_name.value;
            // console.log(updatedValue);
            // console.log(id)

            const table_sensfrx_input_id = document.getElementById('custom_custom_sensfrx_tab_&_slug_one_update_id_1');
            table_sensfrx_input_id.value = id;
            const table_sensfrx_input_updated_name = document.getElementById('custom_custom_sensfrx_tab_&_slug_one_update');
            table_sensfrx_input_updated_name.value = updatedValue;
            setTimeout(function() {
                document.getElementById("custom_custom_sensfrx_tab_&_slug_button_update").click();
            }, 100); 
            
        }
    }

    function sensfrx_custom_custom_tab_delete(sensfrx_delete_id){
        var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        if (button) {
            button.click();
        } else {
            console.error('Save Change Button not found');
        }

        if(sensfrx_delete_id !== null && sensfrx_delete_id !== undefined) {
            // console.log(sensfrx_delete_id);
            const table_sensfrx_input_updated_name = document.getElementById('custom_custom_sensfrx_tab_&_slug_one_delete_id_1');
            table_sensfrx_input_updated_name.value = sensfrx_delete_id;
            setTimeout(function() {
                document.getElementById("custom_custom_sensfrx_tab_&_slug_delete_update").click();
            }, 100); 
        }
    }

</script>



