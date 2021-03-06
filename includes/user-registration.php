<?php

/* 
 * Copyright (C) 2015 sunyata
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

    

/*
 * Text area containing terms and conditions that will be shown at caller user
 * registration
 */
function ea_terms_and_conditions_textarea(){
    ?>
    
    <textarea rows="5" cols="28" readonly="true" draggable="false" style="resize: none">Please enter the terms and conditions here (either directly or in another way). now entering some text to get more to see how it looks. now entering some text to get more to see how it looks. now entering some text to get more to see how it looks, now entering some text to get more to see how it looks. now entering some text to get more to see how it looks</textarea>
    <br>
    <input id="termsCheckbox" name="termsCheckbox" type="checkbox" value="1">
    <label for="termsCheckbox">I accept these terms and conditions</label>

    <?php
}
add_action('register_form', 'ea_terms_and_conditions_textarea');



/*
 * WP filter that validates that the skype name exists, if it doesn't an
 * error is added to the list of registration errors
 */
function ea_validate_skype_name($modErrors, $iSkypeName, $iUserEmail){
    // Using curl to do a http post request to skype
    $tUrl = "https://login.skype.com/json/validator";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tUrl);
    curl_setopt($ch, CURLOPT_POST , 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
    curl_setopt($ch, CURLOPT_POSTFIELDS , "new_username=$iSkypeName");
    $tResponse = curl_exec($ch);
    $tResultInfo = curl_getinfo($ch);
    curl_close($ch);
    // Check if the skype name is avalilable for registration (meaning that no user has it)..
    if( substr_count($tResponse, "not available") == 0 ){
        // ..if so, add skype error to the list of registration errors
        $modErrors -> add('skype_error', __('<strong>ERROR:</strong> Skype name could not be verified, please recheck'), 'domain1');
        //-domain?? doesn't seem to matter what we choose here
    }
    return $modErrors;
}
add_filter('registration_errors', 'ea_validate_skype_name', 10, 3);

function ea_validate_terms_accepted($modErrors, $iSkypeName, $iUserEmail){
    //echo "<h3>checkbox: " . $_POST[termsCheckbox] . "</h3>";
    if(!isset($_POST[termsCheckbox])){ //-if checkbox false this value will not even be set
        $modErrors -> add('terms_error', __('<strong>ERROR:</strong> You must accept the terms and conditions to register'), 'domain1');
    }
    return $modErrors;
}
add_filter('registration_errors', 'ea_validate_terms_accepted', 10, 3);
