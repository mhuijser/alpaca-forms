alpaca-forms
===========

# What does it do ?

The alpaca-forms plugin is a small plugin for wordpress that generates a form on your page using alpacajs  (http://www.alpacajs.org/). On submission, the values of the form are gathered and mailed.

# Installation

Download the zip (Press "Download ZIP"), and install it as a plugin in your wordpress instance

# Use

1) Put your alpacajs javascript configuration in the js-dir (take a look at the example in the js-folder)

2) Use the following shortcode to implement the form in a wordpress page:

`[alpacaform
  from_email="noreply@example.com"
  from_name="Nice Name"
  to_email="mail@example.com"
  subject="Form"
  form="declaration"
  thankyou_message="Thanks for your submission!"]`
  
  
  


Note: A field with name "email" will be added to the cc in the email that will be sent.

