function showDiv(showpermit, element)
{
    document.getElementById(showpermit).style.display = element.value == Yes ? 'block' : 'none';
}