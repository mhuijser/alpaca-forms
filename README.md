alpaca-forms
===========

A small plugin for wordpress to make it easily possible to maintain some forms based on alpacajs.

1) Put your alpaca javascript configuration in the js-dir (take a look at the example);
2) Use the following shortcode to implement the form in a wordpress page:

[alpacaform
  from_email="noreply@example.com"
  from_name="Nice Name"
  to_email="mail@example.com"
  subject="Form"
  form="declaration"
  thankyou_message="Thanks for your submission!"]


Note: A field with name "email" will be added to the cc in the email that will be sent.

