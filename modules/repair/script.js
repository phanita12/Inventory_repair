function initRepairGet() {
  var o = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value;
    },
    
     onSuccess: function() {
      topic.valid();
      product_no.valid();
      category.valid();
      type_repair.valid();
      model.valid(); 
   
    },
    onChanged: function() {
      topic.reset();
      product_no.reset();
      category.reset();
      type_repair.reset(); 
      model.reset();
     
    }
  };

  /*---------------------------Moomai---------------------- */
  var a = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value; 
    },
    onSuccess: function() {   
      category.valid();
    },
    onChanged: function() {
      category.reset();
    }
  };
  var b = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value; 
    },
    onSuccess: function() {   
      type_repair.valid();
    },
    onChanged: function() {
      type_repair.reset();
    }
  };
  var c = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value; 
    },
    onSuccess: function() {   
      model.valid();
    },
    onChanged: function() {
      model.reset();
    }
  };

  var d = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value; 
    },
    onSuccess: function() {   
      approve.valid();
    },
    onChanged: function() {
      approve.reset();
    }
  };


  var topic = initAutoComplete(
    "topic",
    WEB_URL + "index.php/repair/model/autocomplete/find",
    "topic,product_no",
    "find",
    o
  );
  var product_no = initAutoComplete(
    "product_no",
    WEB_URL + "index.php/repair/model/autocomplete/find",
    "product_no,topic",
    "find",
    o
  );
  
  var category = initAutoComplete(
    "category",
    WEB_URL + "index.php/repair/model/autocomplete/find",
    "category",
    "find",
    a 
  );
  
  var type_repair = initAutoComplete(
    "type_repair",
    WEB_URL + "index.php/repair/model/autocomplete/find",
    "type_repair",
    "find",
    b 
  );
  var model = initAutoComplete(
    "model",
    WEB_URL + "index.php/repair/model/autocomplete/find",
    "model",
    "find",
    c
  );
  var approve = initAutoComplete(
    "approve",
    WEB_URL + "index.php/repair/model/autocomplete/find",
    "approve",
    "find",
    d
  );
 
 
}