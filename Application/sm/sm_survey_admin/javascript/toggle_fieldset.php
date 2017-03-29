<?php
//Toggles the display of the contents of a fieldset
?>
<script>
function toggle_fieldset(id)
{
    t = document.getElementById(id);
    if(t.style.display == "none")
    {
        t.style.display = "block";
    }
    else
    {
        t.style.display = "none";
    }
}
</script>
