/*
 * Register a few dutch translations
 */
jQuery.alpaca.registerMessages({
   "disallowValue": "{0} zijn niet toegestane waarden.",
   "notOptional": "Dit veld is verplicht.",
   "invalidEmail": "Verkeerd emailadres. Bijvoorbeeld: info@rafaelalmere.nl"
});

jQuery(document).ready(function() {
    var now = new Date();
    jQuery("#form").alpaca({
      "data": {
          "datum": now.getDate() + "/" + (now.getMonth()+1) + "/" + now.getFullYear(),
          "betaalwijze" : "Bank",
      },
      "schema": {
          "title":"Declaratie formulier.",
          "description":"We willen graag alles van je weten.",
          "type":"object",
          "properties": {
              "naam": {
                  "type":"string",
                  "title":"Naam",
                  "required":true
              },
              "adres": {
                  "type":"string",
                  "title":"Adres",
                  "required":true
              },
              "postcode": {
                  "type":"string",
                  "title":"Postcode",
                  "required":true
              },
              "woonplaats": {
                  "type":"string",
                  "title":"Woonplaats",
                  "required":true
              },
              "email" : {
                  "type":"string",
                  "title":"Uw emailadres",
                  "format" : "email",
                  "required":true
              },
              "datum": {
                  "type": "string",
                  "title":"Datum",
                  "readonly":true
              },
              "betaalwijze": {
                  "type": "string",
                  "title" : "Betaalwijze",
                  "required" : true,
                  "enum": ["Contant", "Bank"]
              },
              "rekeningnummer" : {
                  "type": "string",
                  "title" : "IBAN rekeningnummer",
                  "dependencies": "betaalwijze"
              },
              "declaratieregels" : {
                  "description": "Vul voor ieder bonnetje/factuur een declaratieregel in.",
                  "type": "array",
                  "items": {
                      "title": "Declaratieregel",
                      "type": "object",
                      "properties": {
                          "kostenplaats": {
                              "title": "Kostenplaats",
                              "description": "Kies een Kostenplaats.",
                              "type": "string",
                              "required" : true,
                              "enum": [
                                  "4230: Drukwerk algemeen",
                                  "4240: Onderwijs",
                                  "4260: Faciliteiten",
                                  "4540: Sprekers",
                                  "4550: Catering",
                                  "4560: Audiovisueel",
                                  "4810: Communicatie & Media",
                                  "4890: Diversen/onvoorzien"
                              ]
                          },
                          "omschrijving": {
                              "title": "Omschrijving",
                              "type": "string"
                          },
                          "bedrag": {
                              "title": "Bedrag",
                              "description": "Bedrag in € ",
                              "type": "number"
                          },
                          "nota": {
                              "title": "Bon of factuur",
                              "type": "string",
                              "format": "uri",
                              "label": "Bon of factuur"
                          }
                      }
                  }
              }
          }
      },
      "options": {
          "form":{
              "attributes":{
                  "action":document.URL,
                  "method":"post",
                  "enctype": "multipart/form-data"
              },
              "buttons":{
                  "submit":{
                    "title" : "Declaratie indienen"
                  }
              },
              "toggleSubmitValidState": false
          },
          "helper": "Vul de onderstaande velden in.",
          "hideInitValidationError": true,
          "fields": {
              "naam": {
                  "size": 20,
                  "placeholder": "Jozef Jacobsen"
              },
              "adres": {
                  "size": 20,
                  "placeholder": "De sphinx 12"
              },
              "postcode": {
                  "size": 12,
                  "placeholder": "1320 AA"
              },
              "woonplaats": {
                  "size": 20,
                  "placeholder": "Cairo"
              },
              "datum": {
                  "size": 20
              },
              "betaalwijze": {
                  "type" : "select",
                  "emptySelectFirst": true
              },
              "rekeningnummer" : {
                  "dependencies": {
                      "betaalwijze": "Bank"
                  },
                  "size": 20
              },
              "declaratieregels" : {
                  "label": "Declaratieregels",
                  "items": {
                      "addItemLabel": "Voeg een declaratieregel toe"
                  },
                  "fields": {
                      "item": {
                          "fields": {
                              "kostenplaats_gemeente": {
                                  "type" : "select"
                              },
                              "omschrijving" : {
                                  "placeholder" : "Omschrijf je declaratie beknopt maar duidelijk."
                              },
                              "bedrag" : {
                                  "type" : "currency",
                                  "centsSeparator": ".",
                                  "prefix": "",
                                  "suffix": "",
                                  "thousandsSeparator": "",
                              },
                              "nota" : {
                                  "type": "file"
                              }
                          }
                      }
                  }
              }
          }
      },
      "view": {
          "parent": "bootstrap-edit"
      },
       "postRender": function(renderedField) {
           var form = renderedField.form;
           if (form) {
               form.registerSubmitHandler(function(e, form) {
                   // validate the entire form (top control + all children)
                   form.validate(true);
                   // draw the validation state (top control + all children)
                   form.refreshValidationState(true);
                   // now display something
                   if (form.isFormValid()) {
                       var value = form.getValue();
                       return true;
                   } else {
                       alert("Het lijkt erop dat niet alle velden correct zijn ingevuld. Controleer je invoer en probeer opnieuw ...");
                   }
                   e.stopPropagation();
                   return false;
               });
           }
       }
  });

});