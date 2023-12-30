<?php
function estilos(){
    ?>
    <style>
.sectionfilemanager{
    width: 100%;
}
.sectionfilemanager input[type="submit"]{
    border-radius: 2px;
    border-color:rgb(92,92,92)!important;
    border-style: 1px!important;
    color: #3d3d3d;
    background-color: rgb(238,238,238);
    padding: 3px;
    padding-left:5px;
    padding-right:5px;
}
.sectionfilemanager input[type="submit"]:hover{
    border-color:#1c9ad4!important;
    background-color:#1c9ad4;
    color:white;
}
.sectionfilemanager .download{
    color:#1c9ad4;
    font-weight: 500;
}
.sectionfilemanager .categoria{
    margin-right: 2%;
    font-weight: 500;
}
.sectionfilemanager .categoria:hover{
    color:#1c9ad4;
}
.sectionfilemanager a{
    color: #6e6e6e;
     text-decoration: none!important;
}
.sectionfilemanager .activo{
    color:#2980b9;
}

.sectionfilemanager form{
    margin-top:5%;
}
 
.sectionfilemanager th{
    color:#6e6e6e;
}

/* Estilos para el label que simula el botón de selección */
.sectionfilemanager .label-file {
  padding: 8px 12px;
  background-color: #3498db;
  color: #fff;
  border-radius: 4px;
  cursor: pointer;
}

/* Cambiar estilos al pasar el cursor sobre el label */
.sectionfilemanager .label-file:hover {
  background-color: #2980b9;
}
.sectionfilemanager .menu_mobile{
    margin-bottom: 20px;
}
/*mobile section*/

@media (min-width: 0px) and (max-width: 750px) {
  
  /* CSS */
  .sectionfilemanager{
    overflow: scroll;
    height: 20em;
    width: auto;
  }
  .sectionfilemanager  .menu_mobile{
    overflow: scroll;
    margin-bottom: 10%!important;
  }
  .sectionfilemanager  .menu_mobile_section{
    width: 45em;
    height: 46px;
  }
}
</style>
<?php } ?>