# woocommerce-admin-columns-custom
Custom script fixing the way woocommerce admin columns displays last order by date.
Changes the way the "public function format" function returns data. Previously this was broken. 

### Old method (broken): 
$value = $order->get_date_completed()->getTimestamp();

### New method:
$value = $order->get_date_created()->format ('Y-m-d');


Then, set "Last Order" column to display by Date as usual. Adjust the formatting above within the function per your own specs.
