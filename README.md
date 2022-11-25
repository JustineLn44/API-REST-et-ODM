# API-REST-et-ODM

### <p>Install BDD :</br> 
php bin/console doctrine:database:create</br>
php bin/console make:migration</br>
php bin/console doctrine:migrations:migrate</p>

### Routes :
#### Show all products : </br>
  - Route : /products </br>
  - Method : GET </br>
  - Name : app_products </br>
#### Add new product : 
  - Route : /product/add 
  - Method : POST
  - Name : app_add_product
  - Elements : 
    - name
    - stock
#### Show a product : 
  - Route : /product/show/{id}
  - Method : GET
  - Name : app_show_product
#### Edit a product : 
  - Route : /product/edit/{id}
  - Method : PUT
  - Name : app_edit_product
  - Elements : 
    - name
    - stock
#### Delete a product : 
  - Route : /product/delete/{id}
  - Method : POST
  - Name : app_delete_product
#### Edit the product stock : 
  - Route : /product/editStock/{id} 
  - Method : PUT
  - Name : app_editStock_product
  - Elements : 
    - editStock
