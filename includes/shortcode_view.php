<?php 
    global $wpdb;
    $sql  = "SELECT * FROM {$wpdb->prefix}nyt_main_table";
    $data = $wpdb->get_results( $sql );
?>
<table>
    <thead>
        <tr>
            <td>Title</td>
            <td>Description</td>
            <td>Contdibutor</td>
            <td>Author</td>
            <td>Price</td>
            <td>Publisher</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $item): ?>
            <tr>
                <td> <?php echo $item->title ?> </td>            
                <td> <?php echo $item->book_description ?> </td>            
                <td> <?php echo $item->contributor ?> </td>            
                <td> <?php echo $item->author ?> </td>            
                <td> <?php echo $item->price ?> </td>            
                <td> <?php echo $item->publisher ?> </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>