# Snippet personalizzati per Jilt
## Transactional - Rimborsato 
link alla mail su Jilt: [Transactional - Rimborsato](https://app.jilt.com/shops/38254/campaigns/112991/emails/214239)

### Intro
```javascript
{{ customer.first_name | prepend:"Ciao " | append:", " || default: "Eccoci" }}abbiamo disposto il rimborso per l'ordine {{ order.formatted_number }}. Qui sotto i dati dell'ordine.
```
### Alcuni nostri prodotti
#### campo titolo:
mando a capo solo il Low Carb, perché va su due righe così si allinea agli altri il prezzo

```javascript
{% if product.id != 156 %} 
{{ product.title }} 
{% else %} Preparato per Pane 
Low Carb {% endif %}
```
#### campo descrizione:
aggiungo il 10% di iva con questa funzioncina

```javascript
{{ product.price | times:1.1 | money }}
```

