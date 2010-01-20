<?php /* #?ini charset="utf-8"?

[AdminSettings]
# Should it be possible to change orderlines for one customer
# Options:
# * true
# * false
CanEditProductItems=false

ProductClasses[]
ProductClasses[]=product

[ProductClassSettings_product]
Depth=3
ParentNodeID=2
FieldToSearchIn[]
FieldToSearchIn[]=name
Preview=name


[WebShopUser]

# The node ID of the user group which contains the various shop type user groups
ParentShopUserGroupNodeID=

# The IDs of the user class, the section in which users should be put, and the
# user which should be the creator of these accounts. If your have an out-of-the-box
# eZ Publish setup, these settings can stay as-is.
UserClassID=4
DefaultSectionID=2
UserCreatorID=10

# Set if you want to send the user an confirmation when he/her has registred a new user.
# 1=send, 0=do not send
SendConfirmationMail=0

[General]

# The country which should be selected in the drop down by default if the user
# can select a contry when providing a delivery and/or an invoice address.
DefaultCountry=NO

TermsNodeID=54
TermsDataMapIdentificator=handlevilkaar

# Activate utf8_decode on fetching data.
ActivateUTF8Decode=0

# Specify a list of the form fields you want to use as default. These will act
# as your base, from which you can subtract for each client type.
# Here is a complete liste of form fields you can use:
# * company
# * vat_no
# * first_name
# * last_name
# * phone
# * email
# * delivery_street
# * delivery_zip
# * delivery_city
# * delivery_state
# * delivery_country
# * invoice_street
# * invoice_zip
# * invoice_city
# * invoice_state
# * invoice_country
# * delivery
# * payment
# * comment
# * terms
#
# If you need more fields just add them in the DefaultFormFields[].
# Also you need to activate the override for the register_form.tpl, follow the instructions in docs/INSTALL.txt Under the section CUSTOMIZE

DefaultFormFields[]
DefaultFormFields[]=company
DefaultFormFields[]=vat_no
DefaultFormFields[]=first_name
DefaultFormFields[]=last_name
DefaultFormFields[]=phone
DefaultFormFields[]=email
DefaultFormFields[]=delivery_street
DefaultFormFields[]=delivery_zip
DefaultFormFields[]=delivery_city
DefaultFormFields[]=invoice_street
DefaultFormFields[]=invoice_zip
DefaultFormFields[]=invoice_city
DefaultFormFields[]=delivery
#DefaultFormFields[]=payment
#DefaultFormFields[]=terms

# A list of delivery methods you want to provide. These will act as a base
# from which you can subtract for each client type. Specify each delivery
# method with the name you want to show the user, and an identificator, 
# separated by a semicolon
DefaultDeliveryMethods[]
DefaultDeliveryMethods[]=Postoppkrav;postoppkrav
DefaultDeliveryMethods[]=D¿r til d¿r;door_to_door
DefaultDeliveryMethods[]=Hentes i Industriveien 1, Skedsmokorset;hentes

# A list of payment methods you want to provide. These will act as a base
# from which you can subtract for each client type. Specify each payment
# method with the name you want to show the user, and an identificator, 
# separated by a semicolon
DefaultPaymentMethods[]


# For each client type, you can have an optionl ClientGroup block. The block
# should be named "ClientGroup_NODEID" where NODEID is the node of the
# user group which will hold the users for this client type.
[ClientGroup_NODEID]

# If you want to subtract form fields for this client type from the DefaultFormFields
# specify a list of the form fields you would like to subtract here.
ExcludeFormFields[]
ExcludeFormFields[]=company
ExcludeFormFields[]=vat_no

# A list of validation rules for each form field. Separate form field name and
# validation rules by a semilcolon, and each validation rule by a pipe.
# For a list of available validation rules, check out the nmvalidation extension
ValidationRules[]
ValidationRules[]=first_name;not_empty
ValidationRules[]=last_name;not_empty
ValidationRules[]=phone;numbers_only
ValidationRules[]=email;not_empty|email_syntax
ValidationRules[]=delivery_zip;not_empty|numbers_only
ValidationRules[]=delivery_city;not_empty
ValidationRules[]=invoice_zip;not_empty|numbers_only
ValidationRules[]=invoice_city;not_empty
ValidationRules[]=delivery;not_empty
#ValidationRules[]=terms;choose_item_not_zero
# If you want to subtract delivery methods for this client type from the DefaultDeliveryMethods
# specify a list of the delivery methods you would like to subtract here.
ExcludeDeliveryMethods[]
ExcludeDeliveryMethods[]=door_to_door

# If you want to subtract payment methods for this client type from the DefaultPaymentMethods
# specify a list of the payment methods you would like to subtract here.
ExcludePaymentMethods[]

# List of data that should not be copied from previous order to a new order.
ExcludeDataForCopyOrder[]

*/ ?>
