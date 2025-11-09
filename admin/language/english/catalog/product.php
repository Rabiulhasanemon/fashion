<?php
// Heading
$_['heading_title']          = 'Products';

// Text
$_['text_success']           = 'Success: You have modified products!';
$_['text_list']              = 'Product List';
$_['text_import']            = 'Import Products';
$_['text_add']               = 'Add Product';
$_['text_edit']              = 'Edit Product';
$_['text_plus']              = '+';
$_['text_minus']             = '-';
$_['text_default']           = 'Default';
$_['text_option']            = 'Option';
$_['text_option_value']      = 'Option Value';
$_['text_percent']           = 'Percentage';
$_['text_variation']         = 'Variation';
$_['text_amount']            = 'Fixed Amount';

// Column
$_['column_name']            = 'Product Name';
$_['column_model']           = 'Model';
$_['column_image']           = 'Image';
$_['column_regular_price']           = 'Regular Price';
$_['column_price']           = 'Price';
$_['column_quantity']        = 'Quantity';
$_['column_sort_order']      = 'Sort Order';
$_['column_status']          = 'Status';
$_['column_date_added']      = 'Date Added';
$_['column_date_modified']   = 'Date Modified';
$_['column_action']          = 'Action';

// Entry
$_['entry_name']             = 'Product Name';
$_['entry_sub_name']         = 'Product Sub Name';
$_['entry_description']      = 'Description';
$_['entry_short_description']= 'Short Description';
$_['entry_meta_title'] 	     = 'Meta Tag Title';
$_['entry_meta_keyword'] 	 = 'Meta Tag Keywords';
$_['entry_meta_description'] = 'Meta Tag Description';
$_['entry_keyword']          = 'SEO Keyword';
$_['entry_model']            = 'Model';
$_['entry_sku']              = 'SKU';
$_['entry_mpn']              = 'MPN';
$_['entry_short_note']       = 'Short Note';
$_['entry_emi']              = 'Available EMI';
$_['entry_shipping']         = 'Requires Shipping';
$_['entry_manufacturer']     = 'Manufacturer';
$_['entry_attribute_profile']= 'Attribute Profile';
$_['entry_filter_profile']   = 'Filter Profile';
$_['entry_store']            = 'Stores';
$_['entry_date_available']   = 'Date Available';
$_['entry_quantity']         = 'Quantity';
$_['entry_minimum']          = 'Minimum Quantity';
$_['entry_maximum']          = 'Maximum Quantity';
$_['entry_stock_status']     = 'Out Of Stock Status';
$_['entry_price']            = 'Price';
$_['entry_cost_price']       = 'Last Selling Price';
$_['entry_regular_price']    = 'Regular Price';
$_['entry_tax_class']        = 'Tax Class';
$_['entry_points']           = 'Points';
$_['entry_option_points']    = 'Points';
$_['entry_subtract']         = 'Subtract Stock';
$_['entry_weight_class']     = 'Weight Class';
$_['entry_weight']           = 'Weight';
$_['entry_dimension']        = 'Dimensions (L x W x H)';
$_['entry_length_class']     = 'Length Class';
$_['entry_length']           = 'Length';
$_['entry_width']            = 'Width';
$_['entry_height']           = 'Height';
$_['entry_image']            = 'Image';
$_['entry_featured_image']   = 'Featured Image';
$_['entry_customer_group']   = 'Customer Group';
$_['entry_date_start']       = 'Date Start';
$_['entry_date_end']         = 'Date End';
$_['entry_priority']         = 'Priority';
$_['entry_attribute']        = 'Attribute';
$_['entry_attribute_group']  = 'Attribute Group';
$_['entry_text']             = 'Text';
$_['entry_option']           = 'Option';
$_['entry_option_value']     = 'Option Value';
$_['entry_required']         = 'Required';
$_['entry_status']           = 'Status';
$_['entry_sort_order']       = 'Sort Order';
$_['entry_parent']         = 'Parent';
$_['entry_category']         = 'Categories';
$_['entry_filter']           = 'Filters';
$_['entry_download']         = 'Downloads';
$_['entry_related']          = 'Related Products';
$_['entry_compatible']          = 'Compatible Products';
$_['entry_tag']          	 = 'Product Tags';
$_['entry_reward']           = 'Reward Points';
$_['entry_layout']           = 'Layout Override';
$_['entry_view']             = 'View';
$_['entry_is_manufacturer_is_parent']             = 'Is manufacturer is parent';

// Import
$_['heading_title_import']   = 'Import Products from CSV';
$_['text_import_info_line1'] = 'Upload a CSV file to add or update products.';
$_['text_import_info_line2'] = 'Images can be local paths under image/ or full URLs (will be downloaded).';
$_['text_import_info_line3'] = 'To update, provide product_id, or an existing model or SKU.';
$_['text_import_info_line4'] = 'Additional images format: path1[:sort]|path2[:sort]';
$_['entry_file']             = 'CSV File';
$_['help_file']              = 'Allowed type: .csv. First row must be headers.';
$_['button_import']          = 'Import CSV';
$_['button_download_sample'] = 'Download Sample CSV';
$_['text_import_success']    = '%s products imported successfully!';
$_['text_import_partial']    = '%s products imported, %s failed.';
$_['error_file']             = 'Please choose a CSV file to upload!';
$_['error_file_read']        = 'Could not read the uploaded file!';

// Help
$_['help_keyword']           = 'Do not use spaces, instead replace spaces with - and make sure the keyword is globally unique.';
$_['help_sku']               = 'Stock Keeping Unit';
$_['help_mpn']               = 'Manufacturer Part Number';
$_['help_manufacturer']      = '(Autocomplete)';
$_['help_minimum']           = 'Force a minimum ordered amount';
$_['help_maximum']           = 'Force a maximum ordered amount';
$_['help_stock_status']      = 'Status shown when a product is out of stock';
$_['help_points']            = 'Number of points needed to buy this item. If you don\'t want this product to be purchased with points leave as 0.';
$_['help_category']          = '(Autocomplete)';
$_['help_filter']            = '(Autocomplete)';
$_['help_download']          = '(Autocomplete)';
$_['help_related']           = '(Autocomplete)';
$_['help_tag']          	 = 'comma separated';

// Error
$_['error_warning']          = 'Warning: Please check the form carefully for errors!';
$_['error_permission']       = 'Warning: You do not have permission to modify products!';
$_['error_name']             = 'Product Name must be greater than 3 and less than 255 characters!';
$_['error_sub_name']             = 'Product Sub Name must be greater than 3 and less than 255 characters!';
$_['error_short_description']  = 'Warning: Review Name must be less then 600 characters!';
$_['error_meta_title']       = 'Meta Title must be greater than 3 and less than 255 characters!';
$_['error_model']            = 'Product Model must be greater than 1 and less than 64 characters!';
$_['error_keyword']          = 'SEO keyword already in use!';
$_['help_points']            = 'Number of points needed to buy this item. If you don\'t want this product to be purchased with points leave as 0.';
$_['help_category']          = '(Autocomplete)';
$_['help_filter']            = '(Autocomplete)';
$_['help_download']          = '(Autocomplete)';
$_['help_related']           = '(Autocomplete)';
$_['help_tag']          	 = 'comma separated';

// Error
$_['error_warning']          = 'Warning: Please check the form carefully for errors!';
$_['error_permission']       = 'Warning: You do not have permission to modify products!';
$_['error_name']             = 'Product Name must be greater than 3 and less than 255 characters!';
$_['error_sub_name']             = 'Product Sub Name must be greater than 3 and less than 255 characters!';
$_['error_short_description']  = 'Warning: Review Name must be less then 600 characters!';
$_['error_meta_title']       = 'Meta Title must be greater than 3 and less than 255 characters!';
$_['error_model']            = 'Product Model must be greater than 1 and less than 64 characters!';
$_['error_keyword']          = 'SEO keyword already in use!';