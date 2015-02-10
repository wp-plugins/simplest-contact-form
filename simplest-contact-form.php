<?php
/*
Plugin Name:Simplest Contact Form
Plugin URI: http://www.demo.webdeveloperutkarsh.in/simplest-contact-form/
Description: Simple WordPress Contact Form
Version: 1.1
Author: Utkarsh Shinde
Author URI: http://www.webdeveloperutkarsh.in
*/


function html_form(){
echo '<form action="' .  get_permalink(). '" method="post">
    <p><label>Your Name</label><br/>
        <input type="text" name="your-name"></p>
    <p><label>Subject</label><br/>
        <input type="text" name="subject"></p>
    <p><label>Email</label><br/>
        <input type="email" name="email"></p>
     <p><label>Message</label><br/>
        <textarea name="message" rows="10" cols="35"></textarea></p>
     <input type="submit" value="Send" name="submit">
    </form>';

}
function deliver_email(){
    if(isset($_POST['submit'])){
        // sanitize form values
       $name= sanitize_text_field($_POST['your-name']);
       $subject= sanitize_text_field($_POST['subject']);
       $email= sanitize_email($_POST['email']);
       $message=esc_textarea( $_POST["message"] );
      
       
       // get the blog administrator's email address
       $to=get_option('admin_email');
     
       $headers="From:$name<$email>";
       // If email has been process for sending, display a success message
       
       if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    
    }
}


//Shortcode:[simplest_contact_form]
function simplest_contact_form_shortcode() {
    ob_start();
    deliver_email();
    html_form();
 return ob_get_clean();
}

add_shortcode( 'simplest_contact_form', 'simplest_contact_form_shortcode' );


class simplest_contact_form extends WP_Widget {

 

    // constructor

   function __construct(){

        parent::__construct(
            'contactform', // Base ID
            __( 'Simplest Contact Form', 'text_domain' ), // Name
            array( 'description' => __( 'Simplest Contact Form Widget for WordPress', 'text_domain' ), ) // Args
        );
   }
    

 

    // widget form creation

  

   function form($instance) {

 

// Check values

if( $instance) {

     $title = esc_attr($instance['title']);

    

} else {

     $title = '';

    

}

?>

 

<p>

<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>

<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

</p>

 



<?php

}


    

 

    // widget update

    function update($new_instance, $old_instance) {

      $instance = $old_instance;

      // Fields

      $instance['title'] = strip_tags($new_instance['title']);

      

     return $instance;

}


 

    // widget display

    function widget($args, $instance) {

      // display widget



   extract( $args );

   // these are the widget options

   $title = apply_filters('widget_title', $instance['title']);

   

   echo $before_widget;

   // Display the widget

   echo '<div class="widget-text wp_widget_plugin_box">';

 

   // Check if title is set

   if ( $title ) {

      echo $before_title . $title . $after_title;

   }

 
echo do_shortcode('[simplest_contact_form]');
  

   echo '</div>';

   echo $after_widget;

}


    

}

 

// register widget

add_action('widgets_init', create_function('', 'return register_widget("simplest_contact_form");'));

?>
