<?php

require_once("config.php");

class Database {

	public $connection;
	public function __construct() {
		$this->open_db_connection();
	}

	public function open_db_connection() {
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->connection->connect_errno) {
			die("Database connection failed!" . $this->connection->connect_error);
		}
	}

	public function query($sql) {
		$result = $this->connection->query($sql);
		$this->confirm_query($result);
		return $result;
	}

	private function confirm_query($result) {
		if(!isset($result)) {
			die("Database query failed!" . $this->connection->error);
		}
	}

	public function escape_string($string) {
		$escaped_string = $this->connection->real_escape_string($string);
		return $escaped_string;
	}

	public function insert_id() {
		return $this->connection->insert_id;
	}

}

$database = new Database();
$database->open_db_connection();


function getAllItemsForUpdate() {

	global $database;

	$query = "SELECT * FROM items ORDER BY item_name ASC";
	$result = $database->query($query);

	echo '<p>&nbsp;</p>';
	echo '<form action="item_update.php" method="POST">';
	echo '<select name="selectId">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$item_name = $row['item_name'];
	 	echo "<option value='$id'>$item_name</option>";
	}

	echo '</select><br><input type="submit" value="Select"></form>';

}

function getAllItemsForDelete() {

	global $database;

	$query = "SELECT * FROM items";
	$result = $database->query($query);

	echo '<select name="item_name" id="item_name">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$item_name = $row['item_name'];
	 	echo "<option value='$id'>$item_name</option>";
	}

	echo '</select>';

}

function createItem() {

	global $database;

	$item_name = $database->escape_string($_POST['item_name']);
	$item_tagline = $database->escape_string($_POST['item_tagline']);

	$query = "INSERT INTO items (item_name, tagline) VALUES ('$item_name', '$item_tagline')";
	$result = $database->query($query);
	$item_id = $database->insert_id();

	$query_all_field_types = "SELECT id FROM field_types";
	$result_all_field_types = $database->query($query_all_field_types);

	while ($row = $result_all_field_types->fetch_assoc()) {
		$field_type_id = $row['id'];

		$query_insert_item_fields = "INSERT INTO item_fields (item_id,field_type) VALUES ($item_id,$field_type_id)";
		$result_insert_item_fields = $database->query($query_insert_item_fields);

	}

    if (isset($result_insert_item_fields)) {
    	$statusMessage = "Item created successfully!";
    } else {
    	$statusMessage = "Error: Item not created!";
    }

    return $statusMessage;

}

function deleteItem() {

	global $database;

	$id = $database->escape_string($_POST['item_name']);

	if (filter_has_var(INPUT_POST, 'deleteme')) {

		$query = "DELETE FROM items WHERE id='$id'";
		$result = $database->query($query);

		$query_delete_item_fields = "DELETE FROM item_fields WHERE item_id=$id";
		$result_delete_item_fields = $database->query($query_delete_item_fields);

		$query_delete_capabilities_fields = "DELETE FROM capabilities_fields WHERE item_id=$id";
		$result_delete_capabilities_fields = $database->query($query_delete_capabilities_fields);

	}

    if (isset($result_delete_capabilities_fields)) {
    	$statusMessage = "Item deleted successfully!";
    } else {
    	$statusMessage = "Error: Item not deleted!";
    }

    return $statusMessage;

}

function getAllFieldTypesForUpdate() {

	global $database;

	$query = "SELECT * FROM field_types ORDER BY display_order ASC";
	$result = $database->query($query);

	echo '<p>&nbsp;</p>';
	echo '<form action="field_types_update.php" method="POST">';
	echo '<select name="selectId">';	

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$type = $row['type'];
	 	echo "<option value='$id'>$type</option>";
	}

	echo '</select><br><input type="submit" value="Select"></form>';

}

function getAllFieldTypesForDelete() {

	global $database;

	$query = "SELECT * FROM field_types";
	$result = $database->query($query);

	echo '<select name="type" id="type">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$type = $row['type'];
	 	echo "<option value='$id'>$type</option>";
	}

	echo '</select>';

}

function getSingleItemForUpdate($selectId) {

	global $database;

	echo '<form action="item_update.php" method="POST">';
	echo '<div class="form-group">';

	$query_tagline = "SELECT tagline FROM items WHERE id = $selectId";
	$result_tagline = $database->query($query_tagline);

	while ($row0 = $result_tagline->fetch_assoc()) {
		$item_tagline = $row0['tagline'];
		echo "<h1>Subtitle / Tagline</h1>";
		echo '<textarea id="'.$selectId.'" name="'.$selectId.'" rows="1" cols="1" class="form-control">';
		echo $item_tagline;
		echo '</textarea>';

	}

	$query = "SELECT * FROM item_fields WHERE item_id = $selectId";	
	$result = $database->query($query);

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$item_id = $row['item_id'];
		$field_type = $row['field_type'];
		$field_text = $row['field_text'];	 	

	 		$query_field_type = "SELECT * FROM field_types WHERE id = $field_type";
	 		$result2 = $database->query($query_field_type);

	 		while ($row2 = $result2->fetch_assoc()) {
	 			//$field_id2 = $row2['id'];
	 			$field_type2 = $row2['type'];
	 			echo "<h1>$field_type2</h1>";	 			
	 			echo '<textarea id="'.$id.'" name="'.$id.'" rows="3" cols="1" class="ckeditor">';
	 			echo $field_text;
	 			echo '</textarea>';
	 		} 	

	}


	$query_sections = "SELECT id,name,description FROM sections ORDER BY display_order";
	$result_sections = $database->query($query_sections);

	while ($row1 = $result_sections->fetch_assoc()) {
		$section_id = $row1['id'];
		$section_name = $row1['name'];
		$section_description = $row1['description'];

		echo "<br><span style='font-size: 14px; font-family: Arial;'><b>$section_name</b></span><br>";

		$query_capabilities = "SELECT * FROM capabilities WHERE nav_menu_section=$section_id";
		$result_capabilities = $database->query($query_capabilities);		

		while ($row = $result_capabilities->fetch_assoc()) {
			$id_checked = $row['id'];
			$capability_checked = $row['capability'];

			$query_checked = "SELECT id FROM capabilities_fields WHERE item_id='$selectId' AND capability_id='$id_checked'";
			$result_checked = $database->query($query_checked);

			$checked_row = $result_checked->fetch_row();

			if (isset($checked_row)) {

				echo "<input type='checkbox' name='capabilities[]' value='$id_checked' checked>";
				echo "&nbsp;<label for='capabilities'>$capability_checked</label><br>";

			} else {

				echo "<input type='checkbox' name='capabilities[]' value='$id_checked'>";
				echo "&nbsp;<label for='capabilities'>$capability_checked</label><br>";

			}

		}

	}
	
	echo '</div><br>';
	echo '<input type="hidden" id="saveId" name="saveId" value="'.$item_id.'">';
	echo '<input class="btn btn-primary" type="submit" name="submit" value="Save">';
	echo '</form>';

}

/*function getSingleItemForUpdate($selectId) {

	global $database;

	echo '<form action="item_update.php" method="POST">';
	echo '<div class="form-group">';

	$query_tagline = "SELECT tagline FROM items WHERE id = $selectId";
	$result_tagline = $database->query($query_tagline);

	while ($row0 = $result_tagline->fetch_assoc()) {
		$item_tagline = $row0['tagline'];
		echo "<h1>Subtitle / Tagline</h1>";
		echo '<textarea id="'.$selectId.'" name="'.$selectId.'" rows="1" cols="1" class="form-control">';
		echo $item_tagline;
		echo '</textarea>';

	}

	$query = "SELECT * FROM item_fields WHERE item_id = $selectId";	
	$result = $database->query($query);

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$item_id = $row['item_id'];
		$field_type = $row['field_type'];
		$field_text = $row['field_text'];	 	

	 		$query_field_type = "SELECT * FROM field_types WHERE id = $field_type";
	 		$result2 = $database->query($query_field_type);

	 		while ($row2 = $result2->fetch_assoc()) {
	 			$field_id2 = $row2['id'];
	 			$field_type2 = $row2['type'];
	 			echo "<h1>$field_type2</h1>";	 			
	 			echo '<textarea id="'.$id.'" name="'.$id.'" rows="3" cols="1" class="ckeditor">';
	 			echo $field_text;
	 			echo '</textarea>';
	 		} 	

	}

////////////////////
	$query_sections = "SELECT id,name,description FROM sections ORDER BY display_order";
	$result_sections = $database->query($query_sections);

	while ($row1 = $result_sections->fetch_assoc()) {
		$section_id = $row1['id'];
		$section_name = $row1['name'];
		$section_description = $row1['description'];

		echo "<span style='font-size: 14px; font-family: Arial;'><b>$section_name</b></span><br>";

	}
///////////////////



	$query_capabilities = "SELECT * FROM capabilities";
	$result_capabilities = $database->query($query_capabilities);

	echo '<p>&nbsp;</p>';

	while ($row = $result_capabilities->fetch_assoc()) {
		$id_checked = $row['id'];
		$capability_checked = $row['capability'];

		$query_checked = "SELECT id FROM capabilities_fields WHERE item_id='$selectId' AND capability_id='$id_checked'";
		$result_checked = $database->query($query_checked);

		$checked_row = $result_checked->fetch_row();

		if (isset($checked_row)) {

			echo "<input type='checkbox' name='capabilities[]' value='$id_checked' checked>";
			echo "<label for='capabilities'>$capability_checked</label><br>";

		} else {

			echo "<input type='checkbox' name='capabilities[]' value='$id_checked'>";
			echo "<label for='capabilities'>$capability_checked</label><br>";

		}


	}
	
	echo '</div><br>';
	echo '<input type="hidden" id="saveId" name="saveId" value="'.$item_id.'">';
	echo '<input class="btn btn-primary" type="submit" name="submit" value="Save">';
	echo '</form>';

}*/

function getSingleFieldTypeForUpdate($editId) {

	global $database;
	
	$query = "SELECT * FROM field_types WHERE id = $editId ORDER BY display_order ASC";
	$result = $database->query($query);

	echo '<form action="field_types_update.php" method="POST">';
	echo '<div class="form-group">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$type = $row['type'];

		echo "<h1>$type</h1>";		
		echo '<input type="text" id="type" name="type" value="'.$type.'" class="form-control">';

	}
	
	echo '</div>';
	echo '<input type="hidden" id="saveId" name="saveId" value="'.$id.'">';
	echo '<input class="btn btn-primary" type="submit" name="submit" value="Save">';
	echo '</form>';

}

function createFieldTypes() {

	global $database;

	$field_type = $database->escape_string($_POST['field_type']);

	$query = "INSERT INTO field_types (type) VALUES ('$field_type')";
	$result = $database->query($query);
	$field_type = $database->insert_id();

	$query_all_items = "SELECT id FROM items";
	$result_all_items = $database->query($query_all_items);

	while ($row = $result_all_items->fetch_assoc()) {
		$item_id = $row['id'];

		$insert_all_query = "INSERT INTO item_fields (item_id,field_type) VALUES ($item_id,$field_type)";
		$result_all_query = $database->query($insert_all_query);

	}

    if (isset($result_all_query)) {
    	$statusMessage = "Field type created successfully!";
    } else {
    	$statusMessage = "Error: Field type not created!";
    }

    return $statusMessage;

}

function deleteFieldType() {

	global $database;

	$id = $database->escape_string($_POST['type']);

	if (filter_has_var(INPUT_POST, 'deleteme')) {

		$query = "DELETE FROM field_types WHERE id='$id'";
		$result = $database->query($query);

		$query_delete_item_fields = "DELETE FROM item_fields WHERE field_type=$id";
		$result_delete_item_fields = $database->query($query_delete_item_fields);

	}

    if (isset($result_delete_item_fields)) {
    	$statusMessage = "Field type deleted successfully!";
    } else {
    	$statusMessage = "Error: Field type not deleted!";
    }

    return $statusMessage;

}

function updateSingleItem($saveId) {

	global $database;

	if ($_POST['saveId']) {
		$saveId = $database->escape_string($_POST['saveId']);
	}	

	foreach ($_POST as $key => $value) {		

		if ((!is_array($value)) && $value != "Save") {

			$query = "UPDATE item_fields SET field_text='$value' WHERE id='$key'";
			$result = $database->query($query);

			$query_tagline = "UPDATE items SET tagline='$value' WHERE id='$key'";
			$result_tagline = $database->query($query_tagline);

		}

	}

	$query_clear_capabilities = "DELETE FROM capabilities_fields WHERE item_id='$saveId'";
	//$result_clear_capabilities = $database->query($query_clear_capabilities);
	$database->query($query_clear_capabilities);

	foreach ($_POST['capabilities'] as $checkedKey => $checkedValue) {

		$query_insert_capabilities_fields = "INSERT INTO capabilities_fields VALUES ('','$saveId','$checkedValue')";
		$result_insert_capabilities_fields = $database->query($query_insert_capabilities_fields);

	}

    if (isset($result_insert_capabilities_fields)) {
    	$statusMessage = "Item updated successfully! Just a moment...";
    	$statusMessage .= "<meta http-equiv='refresh' content='3; url=item_update.php' />";
    } else {
    	$statusMessage = "Error: Item not updated!";
    }

    return $statusMessage;

}

function updateSingleFieldType($saveId) {

	global $database;

	$post_type = $database->escape_string($_POST['type']);

	$query = "UPDATE field_types SET type='".$post_type."' WHERE id=".$saveId."";
	$result = $database->query($query);

    if (isset($result)) {
    	$statusMessage = "Field type updated successfully! Just a moment...";
    	$statusMessage .= "<meta http-equiv='refresh' content='3; url=field_types_update.php' />";
    } else {
    	$statusMessage = "Error: Field type not updated!";
    }

    return $statusMessage;

}

function createUser() {

	global $database;

	$username = $database->escape_string($_POST['username']);
	$password = $database->escape_string($_POST['password']);

	$password = crypt($password, SALT); // get SALT from config.php

	$query = "INSERT INTO users (username,password,isadmin) VALUES ('$username','$password',1)";
	$result = $database->query($query);

}

function deleteUser() {

	global $database;

	$id = $database->escape_string($_POST['username']);

	if (filter_has_var(INPUT_POST, 'deleteme')) {

		$query = "DELETE FROM users WHERE id='$id'";
		$result = $database->query($query);

	}

}

function updateUser() {

	global $database;

	$id = $database->escape_string($_POST['username']);
	$password = $database->escape_string($_POST['password']);

	$password = crypt($password, SALT); // get SALT from config.php
	
	$query = "UPDATE users SET password='$password' WHERE id='$id'";
	$result = $database->query($query);

}

function userLogin() {

	global $database;

	$username = $database->escape_string($_POST['username']);
	$password = $database->escape_string($_POST['password']);
	
	$password = crypt($password, SALT); // get SALT from config.php

	$query = "SELECT id FROM users WHERE username='$username' AND password='$password'";
	$result = $database->query($query);

	if ($result->num_rows > 0) {
		$_SESSION['user'] = $username;
		header("location:index.php");
	} else {
		echo "Login failed";
	}

}

function getAllUsersForUpdate() {

	global $database;

	$query = "SELECT * FROM users";
	$result = $database->query($query);

	echo '<select name="username" id="username">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$username = $row['username'];
	 	echo "<option value='$id'>$username</li>";
	}

	echo '</select>&nbsp;';

}

function createCapabilities() {

	global $database;

	$capability = $database->escape_string($_POST['capability']);

	if ($_POST['section']) {
		$section = $database->escape_string($_POST['section']);
	}

	$query = "INSERT INTO capabilities (capability,nav_menu_section) VALUES ('$capability','$section')";
	$result = $database->query($query);

    if (isset($result)) {
    	$statusMessage = "Capability created successfully!";
    } else {
    	$statusMessage = "Error: Capability not created!";
    }

    return $statusMessage;

}

function getSingleCapabilityForUpdate($editId) {

	global $database;

	$query = "SELECT * FROM capabilities WHERE id = $editId";
	$result = $database->query($query);

	echo '<form action="capabilities_update.php" method="POST">';
	echo '<div class="form-group">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$capability = $row['capability'];

		echo "<h1>$capability</h1>";
		echo '<textarea id="capability" name="capability" rows="3" cols="1" class="form-control">';
		echo $capability;
		echo '</textarea>';

	}

	echo '</div>';
	echo '<input type="hidden" id="saveId" name="saveId" value="'.$id.'">';
	echo '<input class="btn btn-primary" type="submit" name="submit" value="Save">';
	echo '</form>';

}

function updateSingleCapability($saveId) {

	global $database;

	$capability = $database->escape_string($_POST['capability']);

	$query = "UPDATE capabilities SET capability='$capability' WHERE id='$saveId'";
	$result = $database->query($query);

    if (isset($result)) {
    	$statusMessage = "Capability updated successfully! Just a moment...";
    	$statusMessage .= "<meta http-equiv='refresh' content='3; url=capabilities_update.php' />";
    } else {
    	$statusMessage = "Error: Capability not updated!";
    }

    return $statusMessage;

}

function getAllCapabilitiesForUpdate() {

	global $database;

	$query = "SELECT * FROM capabilities";
	$result = $database->query($query);

	echo '<p>&nbsp;</p>';
	echo '<form action="capabilities_update.php" method="POST">';
	echo '<select name="selectId">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$capability = $row['capability'];
	 	echo "<option value='$id'>$capability</option>";
	}

	echo '</select><br><input type="submit" value="Select"></form>';

}

function getAllCapabilitiesForDelete() {

	global $database;

	$query = "SELECT * FROM capabilities";
	$result = $database->query($query);

	echo '<select name="capability" id="capability">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$capability = $row['capability'];
	 	echo "<option value='$id'>$capability</option>";
	}

	echo '</select>';

}

function deleteCapability() {

	global $database;

	$id = $database->escape_string($_POST['capability']);

	if (filter_has_var(INPUT_POST, 'deleteme')) {

		$query = "DELETE FROM capabilities WHERE id='$id'";
		$result = $database->query($query);

	}

    if (isset($result)) {
    	$statusMessage = "Capability deleted successfully!";
    } else {
    	$statusMessage = "Error: Capability not deleted!";
    }

    return $statusMessage;

}

function createSections() {

	global $database;

	$section = $database->escape_string($_POST['section']);
	$description = $database->escape_string($_POST['description']);

	$query_max = "SELECT max(display_order) as max_display_order FROM sections";
	$result_max = $database->query($query_max);

	$row = $result_max->fetch_array();
	$max_display_order = $row['max_display_order'];
	$max_display_order++;

	$query = "INSERT INTO sections (name,description,display_order) VALUES ('$section','$description','$max_display_order')";
	$result = $database->query($query);

    if (isset($result)) {
    	$statusMessage = "Section created successfully!";
    } else {
    	$statusMessage = "Error: Section not created!";
    }

    return $statusMessage;

}

function deleteSection() {

	global $database;

	$id = $database->escape_string($_POST['section']);

	if (filter_has_var(INPUT_POST, 'deleteme')) {

		$query = "DELETE FROM sections WHERE id='$id'";
		$result = $database->query($query);

		$query_reset_capability = "UPDATE capabilities SET nav_menu_section=0 WHERE nav_menu_section=$id";
		$result_reset_capability = $database->query($query_reset_capability);

	}

    if (isset($result_reset_capability)) {
    	$statusMessage = "Section deleted successfully!";
    } else {
    	$statusMessage = "Error: Section not deleted!";
    }

    return $statusMessage;

}

function getAllSectionsForDelete() {

	global $database;

	$query = "SELECT * FROM sections";
	$result = $database->query($query);

	echo '<select name="section" id="section">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$section = $row['name'];
	 	echo "<option value='$id'>$section</option>";
	}

	echo '</select>';

}

function getSingleSectionForUpdate($editId) {

	global $database;

	$query = "SELECT * FROM sections WHERE id = $editId";
	$result = $database->query($query);

	echo '<form action="section_update.php" method="POST">';
	echo '<div class="form-group">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$section = $row['name'];
		$description = $row['description'];

		echo "<h1>Section Title</h1>";
		echo '<textarea id="section" name="section" rows="3" cols="1" class="form-control">';
		echo $section;
		echo '</textarea>';

		echo "<h1>Description</h1>";
		echo '<textarea id="description" name="description" rows="3" cols="1" class="ckeditor">';
		echo $description;
		echo '</textarea>';

	}
	
	echo '</div>';
	echo '<input type="hidden" id="saveId" name="saveId" value="'.$id.'">';
	echo '<input class="btn btn-primary" type="submit" name="submit" value="Save">';
	echo '</form>';

}

function updateSingleSection($saveId) {

	global $database;

	$section = $database->escape_string($_POST['section']);
	$description = $database->escape_string($_POST['description']);

	$query = "UPDATE sections SET name='$section',description='$description' WHERE id='$saveId'";
	$result = $database->query($query);

    if (isset($result)) {
    	$statusMessage = "Section updated successfully! Just a moment...";
    	$statusMessage .= "<meta http-equiv='refresh' content='3; url=section_update.php' />";
    } else {
    	$statusMessage = "Error: Section not updated!";
    }

    return $statusMessage;

}

function getAllSectionsForUpdate() {

	global $database;

	$query = "SELECT * FROM sections";
	$result = $database->query($query);

	echo '<p>&nbsp;</p>';
	echo '<form action="section_update.php" method="POST">';
	echo '<select name="selectId">';

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$section = $row['name'];
	 	echo "<option value='$id'>$section</option>";
	}

	echo '</select><br><input type="submit" value="Select"></form>';

}

function getAllSectionsForSelection($editId) {

	global $database;

	$query1 = "SELECT nav_menu_section FROM capabilities WHERE id='$editId'";
	$result1 = $database->query($query1);

	$row1 = $result1->fetch_array();
	$current_nav_menu_section = $row1['nav_menu_section'];

	if (isset($current_nav_menu_section)) {

		$query2 = "SELECT id,name FROM sections WHERE id='$current_nav_menu_section'";
		$result2 = $database->query($query2);

		while ($row2 = $result2->fetch_assoc()) {
			$id2 = $row2['id'];
			$section2 = $row2['name'];
			$additional_option = "<option value='$id2'>$section2</option>";
		}

	}

	$query3 = "SELECT id,name FROM sections";
	$result3 = $database->query($query3);

	echo '<select name="section" id="section">';

	if (isset($additional_option)) {
		echo $additional_option;
	}

	while ($row3 = $result3->fetch_assoc()) {
		$id3 = $row3['id'];
		$section3 = $row3['name'];
		if ((isset($additional_option)) && ($id3 != $id2)) {
			echo "<option value='$id3'>$section3</option>";
		}
	 	
	}

	echo '</select>';

}

function getAllSectionsForCreate() {

	global $database;

	$query1 = "SELECT nav_menu_section FROM capabilities";
	$result1 = $database->query($query1);

	$row1 = $result1->fetch_array();
	$current_nav_menu_section = $row1['nav_menu_section'];

	if (isset($current_nav_menu_section)) {

		$query2 = "SELECT id,name FROM sections WHERE id='$current_nav_menu_section'";
		$result2 = $database->query($query2);

		while ($row2 = $result2->fetch_assoc()) {
			$id2 = $row2['id'];
			$section2 = $row2['name'];
			$additional_option = "<option value='$id2'>$section2</option>";
		}

	}

	$query3 = "SELECT id,name FROM sections";
	$result3 = $database->query($query3);

	echo '<select name="section" id="section">';

	if (isset($additional_option)) {
		echo $additional_option;
	}

	while ($row3 = $result3->fetch_assoc()) {
		$id3 = $row3['id'];
		$section3 = $row3['name'];
		if ((isset($additional_option)) && ($id3 != $id2)) {
			echo "<option value='$id3'>$section3</option>";
		}
	 	
	}

	echo '</select>';

}

function assignToSection($sectionId, $saveId) {

	global $database;

	$query = "UPDATE capabilities SET nav_menu_section='$sectionId' WHERE id='$saveId'";
	$result = $database->query($query);

}

function buildNavMenu() {

	global $database;

	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>';

	echo '
	<script type="text/javascript">
		
	$(parent.document).ready(function () {

	    $("div.tags").find("input:checkbox").live("click", function () {

	   		$(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("font-weight", "");
	   		$(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("color", "");
	   		$(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("border", "");

			if ($(this).prop("input:checked", false)) {
				$(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("font-weight", "");
				$(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("color", "");
				$(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("border", "");
			}
	        
			$("div.tags").find("input:checked").each(function () {
	            $(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("font-weight", "900");
	            $(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("color", "black");
	            $(".cont-checkbox > label." + $(this).attr("rel"), window.parent.document).css("border", "1px solid #003300");
	        });
			
			if (!$("div.tags").find("input:checked").length) {
				$(".cont-checkbox > label", window.parent.document).css("font-weight", "");
				$(".cont-checkbox > label", window.parent.document).css("color", "");
				$(".cont-checkbox > label", window.parent.document).css("border", "");
			}

	    });
	});

	</script>
	';

	$query_sections = "SELECT id,name,description FROM sections ORDER BY display_order";
	$result_sections = $database->query($query_sections);

	$popupCounter = 1;

	while ($row1 = $result_sections->fetch_assoc()) {
		$section_id = $row1['id'];
		$section_name = $row1['name'];
		$section_description = $row1['description'];

	 	echo "<span style='font-size: 14px; font-family: Arial;'><b>$section_name</b>&nbsp;&nbsp;<button class=open$popupCounter style='border-radius: 25px; cursor: pointer; font-weight: bold;'>i</button></span><br>";

	 	// Ugly way to do this, generating JQuery for each iteration.
	 	// So please let me know if you find an alternative.
		echo '

		<script type="text/javascript">
		$(document).ready(function () {
			
			$(".open'.$popupCounter.'").on("click", function() {
			  $(".popup-overlay'.$popupCounter.', .popup-content'.$popupCounter.'").addClass("active");
			});

			$(".close'.$popupCounter.', .popup-overlay'.$popupCounter.'").on("click", function() {
			  $(".popup-overlay'.$popupCounter.', .popup-content'.$popupCounter.'").removeClass("active");
			});
		});
		</script>

		';

	 	echo "
		    <div class='popup-overlay$popupCounter'>
		        <div class='popup-content$popupCounter'>
		        <p>$section_description</p>
		        <button class='Close$popupCounter'>close</button><br/>
		        </div>
		    </div>
	 	";

	 	$popupCounter++;


	 	echo "<div class='tags' style='font-size: 12px; font-family: Arial;'>";

	 		$query_capabilities = "SELECT * FROM capabilities WHERE nav_menu_section=$section_id";
	 		$result_capabilities = $database->query($query_capabilities);

	 		while ($row2 = $result_capabilities->fetch_assoc()) {
	 			$capability_id = $row2['id'];
	 			$capability_name = $row2['capability']; 
	 			$capability_name_formatted = strtolower(str_replace(" ", "_", $capability_name));
	 			$capability_name_formatted = str_replace("/", "_", $capability_name_formatted);
	 			$capability_name_formatted = str_replace("-", "_", $capability_name_formatted);
	 			$capability_name_formatted = str_replace("(", "_", $capability_name_formatted);
	 			$capability_name_formatted = str_replace(")", "_", $capability_name_formatted);
	  			$capability_name_formatted = str_replace(",", "_", $capability_name_formatted);
	 			$capability_name_formatted = str_replace(".", "_", $capability_name_formatted);
	 			
	 			echo "<input type='checkbox' rel='$capability_name_formatted' value='$capability_id'>&nbsp;";
	 			echo "<label for='capability'>$capability_name</label>";
	 			echo "<br>";
	 		}

	 	echo '</div><p>&nbsp;</p>';

	}

}

function populateResults() {

	global $database;

	echo '

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">

	$(document).ready(function () {

	    $(".cont-checkbox label").on("click", function (event) {

	        var $check = $(":checkbox", this);
	        $check.prop("checked", !$check.prop("checked"));

	    	$(".flex-container").remove();
	    	
	    });

	    $(".cont-checkbox :checkbox").on("click", function (event) {
	        event.stopPropagation();
	    	
	        var selected = $("input:checkbox:checked").map(function(){
	            return $(this).val();
	        }).get();

		    $.post("panel.php", {ids: selected}, function(response) {
		      $(".panel").after(response);
		      $(".panel").slideToggle("slow");
		    });

	    });
	});

	</script>
';

	echo '
	<div class="cont-bg">	
	  
	  <div class="cont-main">
	';

	$chkBoxCounter = 1;

	$query_items = "SELECT * FROM items ORDER BY item_name ASC";
	$result_items = $database->query($query_items);

	while ($row1 = $result_items->fetch_assoc()) {
		$item_id = $row1['id'];
		$item_name = $row1['item_name'];
		$item_tagline = $row1['tagline'];

		$query_capabilities_fields = "SELECT capability_id FROM capabilities_fields WHERE item_id=$item_id";
		$result_capabilities_fields = $database->query($query_capabilities_fields);

		$capability_rel_string = "";

		while ($row2 = $result_capabilities_fields->fetch_assoc()) {
			$capability_field_id = $row2['capability_id'];

			$query_capabilities = "SELECT capability FROM capabilities WHERE id=$capability_field_id";
			$result_capabilities = $database->query($query_capabilities);

			$capability = $result_capabilities->fetch_row();
			$capability_name = $capability[0];

 			$capability_name_formatted = strtolower(str_replace(" ", "_", $capability_name));
 			$capability_name_formatted = str_replace("/", "_", $capability_name_formatted);
 			$capability_name_formatted = str_replace("-", "_", $capability_name_formatted);
 			$capability_name_formatted = str_replace("(", "_", $capability_name_formatted);
 			$capability_name_formatted = str_replace(")", "_", $capability_name_formatted);
  			$capability_name_formatted = str_replace(",", "_", $capability_name_formatted);
 			$capability_name_formatted = str_replace(".", "_", $capability_name_formatted);

 			$capability_rel_string .= $capability_name_formatted." ";

			}

		echo "

	    <div class='cont-checkbox'>
	      <input type='checkbox' value='$item_id' id='serviceCheckbox$chkBoxCounter'>
	      <label for='serviceCheckbox$chkBoxCounter' class='$capability_rel_string'>
	      	<img src='images/unchecked.png' width='150'>
	        <span class='cover-checkbox'>
	          <svg viewBox='0.5 1 12 12'>
	            <polyline points='1.5 6 4.5 9 10.5 1'></polyline>
	          </svg>
	        </span>
	        <div class='info'>$item_name</div>
	        <div class='infoSubTitle'>$item_tagline</div>
	    </label>
	    </div>

		";

		$chkBoxCounter++;

	}

echo '</div>';

}

function panelQuery() {

	global $database;

	if (isset($_POST['ids'])) {

		echo '<div class="flex-container">';

		foreach($_POST['ids'] as $post_id) {
			if(!isset($post_id)) {
				continue;
			}

			$query_items = "SELECT * FROM items WHERE id='$post_id'";
			$result_items = $database->query($query_items);

			while ($row1 = $result_items->fetch_assoc()) {
				$item_id = $row1['id'];
				$item_name = $row1['item_name'];

				echo '<div class="flex-child">';

				echo "<br><h3>$item_name</h3>";

				$query_item_fields = "SELECT * FROM item_fields INNER JOIN field_types WHERE field_type=field_types.id AND item_id=$item_id ORDER BY field_types.display_order ASC";
				$result_item_fields = $database->query($query_item_fields);

				while ($row2 = $result_item_fields->fetch_assoc()) {
					$field_type = $row2['field_type'];
					$field_text = $row2['field_text'];
					if ($field_text == "") {
						$field_text = "<p>undefined</p>";
					}

					$query_field_type = "SELECT type FROM field_types WHERE id=$field_type";
					$result_field_type = $database->query($query_field_type);

					while ($row3 = $result_field_type->fetch_assoc()) {
						$field_name = $row3['type'];
						
						echo "<span style='color: green; font-weight: bold;'>$field_name:</span> $field_text";

					}

				}

				echo '</div>';

			}

		}

		echo '</div>';

	} 

}

function allServices() {

	global $database;

	echo '<div class="column">';
	echo '<div class="row">';
	echo '<div class="resultstable">';

	$query_items = "SELECT * FROM items";
	$result_items = $database->query($query_items);

	while ($row1 = $result_items->fetch_assoc()) {
		$item_id = $row1['id'];
		$item_name = $row1['item_name'];

		echo "<br><h3>$item_name</h3>";

		$query_item_fields = "SELECT * FROM item_fields INNER JOIN field_types WHERE field_type=field_types.id AND item_id=$item_id ORDER BY field_types.display_order ASC";
		$result_item_fields = $database->query($query_item_fields);

		while ($row2 = $result_item_fields->fetch_assoc()) {
			$field_type = $row2['field_type'];
			$field_text = $row2['field_text'];

			$query_field_type = "SELECT type FROM field_types WHERE id=$field_type";
			$result_field_type = $database->query($query_field_type);

			while ($row3 = $result_field_type->fetch_assoc()) {

				$field_name = $row3['type'];

				echo '<div class="resultsrow">';
				echo "<div class='resultscell'><span style='color: green; font-weight: bold;'>$field_name:</span> $field_text</div>";
				echo '</div>';

			}		

		}

		echo '</div>';

	}

	echo '</div></div>';


}

function getSectionsForMenu() {

    global $database;

    $query = "SELECT * FROM sections order by display_order ASC";
    $records = $database->query($query);

    $all = array();
    while($data = $records->fetch_assoc())
    {
        $all[] = $data;
    }
    return $all;
}

function saveSectionsForMenu($id, $order) {

    global $database;

    $query = "UPDATE sections SET display_order=".$order." WHERE id=".$id;
    $result = $database->query($query);
    echo $query."<br>";

    if (isset($result)) {
    	$statusMessage = "Menu updated successfully!";
    } else {
    	$statusMessage = "Error: Menu not updated!";
    }

    return $statusMessage;

}

function getFieldTypesForMenu() {

    global $database;

    $query = "SELECT * FROM field_types order by display_order ASC";
    $records= $database->query($query);

    $all = array();
    while($data = $records->fetch_assoc())
    {
        $all[] = $data;
    }
    return $all;
}

function saveFieldTypesForMenu($id, $order) {

    global $database;

    $query = "UPDATE field_types SET display_order=".$order." WHERE id=".$id;
    $records= $database->query($query);

}


?>