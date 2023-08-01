<?php
// Function to get the cart item count
function getCartItemCount($conn, $buyer_id) {
    $buyer_id = mysqli_real_escape_string($conn, $buyer_id);
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart_list WHERE buyer_id = '{$buyer_id}'");
    $row = mysqli_fetch_assoc($result);
    return $row['count'] ?? 0;
}
?>
