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
{% if product.price > 0 %}{{ product.price | times:1.1 | money }}{% else %}in arrivo!{% endif %}
```
---
## Automation - Abandoned cart
link alla mail su Jilt: [Automation - Abandoned cart](https://app.jilt.com/shops/38254/campaigns/114136/emails/216011?campaignType=automation)

### Intro
```javascript
Hai lasciato incustodito il tuo carrello{{ customer.first_name | prepend:"&nbsp;" | append:"&quest;" | default:"&nbsp;" }}
```
---
## Automation - Richiesta recensione
link alla mail su Jilt: [Automation - Richiesta recensione](https://app.jilt.com/shops/38254/campaigns/113000/emails/214373?campaignType=automation)

### Oggetto
```javascript
Ciao{{ customer.first_name | prepend:" " | append:", " | default: "" }}che ne pensi {% if order.line_items[0].sku != "LWC102" %}del {% else %}delle {% endif %}{{ order.line_items[0].title }} Spiga Home? 
```

### Intro - Titolone
```javascript
{{ customer.first_name | prepend:"Ciao " | append:"! " | default: "Eccoci" }}
```

### Testo portante
```javascript
{% if order.line_items.size > 1 %} {% comment %} CICLO CON + RIGHINE {% endcomment %} Hai ordinato alcuni nostri prodotti ormai da un po’ di tempo e siamo curiosi di sapere com’è andata!
{% for item in order.line_items %} {% if item.sku == "LWC101" %}

Com'è venuto il pane fatto con il {{ item.title }}?

🍞Puoi raccontarcelo con una recensione.

{% elsif item.sku == "LWC102" %}

Ti sono piaciute le {{ item.title }}? Hai trovato il sugo perfetto per loro?

🍝 Scrivicelo siamo curiosi.

{% elsif item.sku == "BIOREAL01" %}

🥧Con il {{ item.title }} cos'hai preparato? :)

{% elsif item.sku == "LWC103" %}

💌Com'erano i cracker? Raccontacelo o mandaci una foto.

{% endif %} {% endfor %}{% else %} {% comment %} UN SOLO PRODOTTINO {% endcomment %} {% if order.line_items[0].sku == "LWC101" %} {% comment %} TESTO LOWCARBINO {% endcomment %} hai ordinato il nostro {{ order.line_items[0].title }} Spiga Home ormai da un po’ di tempo e siamo curiosi di sapere com’è andata!
Com'è venuto?

💌 Puoi raccontarci se ti è piaciuto con una recensione.

Se vuoi mandarci la tua ricetta, rispondi a questa mail. 
{% elsif order.line_items[0].sku == "LWC102" %} {% comment %} TESTO TAGLIATELLINE {% endcomment %} hai ordinato le nostre {{ order.line_items[0].title }} Spiga Home ormai da un po’ di tempo e siamo curiosi di sapere com’è andata!

Ti sono piaciute? Hai trovato il sugo perfetto per loro?

💌 Puoi raccontarci se ti sono piaciute con una recensione.

Se vuoi mandarci la tua ricetta preferita, rispondi a questa mail.
{% comment %} RICORDIAMOCI DI GESTIRE IL CASO "ORDINE DEL SOLO LIEVITINO" {% endcomment %} 
{% endif %}{% endif %} Per noi è importante conoscere la tua esperienza e la tua creatività può essere d'ispirazione per altre persone.
```

#### N.B.
aggiungere il link alla recensione:

💌 Puoi raccontarci se ti è piaciuto [con una recensione]({{item.product_url}})

#### Salvo il blocco HTML dell'ordine
tabella che riepiloga gli elementi dell'ordine:
```html
<table align="center" border="0" cellpadding="0" cellspacing="0" width="auto" style="border-collapse: collapse;">
  <thead>
    <tr>
      <td style="vertical-align:middle;color:#000;border-bottom:1px solid #000;padding:10px;width:15%;">Prodotto</td>
      <td style="vertical-align:middle;color:#000;border-bottom:1px solid #000;padding:10px;width:50%;"> </td>
      <td style="vertical-align:middle;color:#000;border-bottom:1px solid #000;padding:10px;width:10%;">Qtà</td>
      <td style="vertical-align:middle;color:#000;border-bottom:1px solid #000;padding:10px;width:25%;">Totale</td>
    </tr>
  </thead>{% for item in order.line_items %}<tr>
    <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;width:15%;"><img src="{{ item.image }}" width="75%" /></td>
    <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;width:50%;">{{ item.title }}</td>
    <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;width:10%;">× {{ item.quantity }}</td>
    <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;width:25%;">{{ item.line_price | money }}</td>
  </tr>{% endfor %}<tfoot>
    <tr>
      <td colspan="3" style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">Subtotale:</td>
      <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">{{ order.subtotal_price | money }}</td>
    </tr>
    <tr>
      <td colspan="3" style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">Imposte:</td>
      <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">{{ order.total_tax | money }}</td>
    </tr>
    <tr style="display:none;">
      <td colspan="3" style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">Tariffa:</td>
      <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">{{ order.total_fees | money }}</td>
    </tr>
    <tr>
      <td colspan="3" style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">Spedizione:</td>
      <td style="vertical-align:middle;color:#888888;border-bottom:1px dotted #ccc;padding:10px;">{{ order.total_shipping | money }}</td>
    </tr>
    <tr>
      <td colspan="3" style="vertical-align:middle;color:#000;border-bottom:2px solid #ccc;border-top:2px solid #ccc;padding:10px;">TOTALE:</td>
      <td style="vertical-align:middle;color:#000;border-bottom:2px solid #ccc;border-top:2px solid #ccc;padding:10px;">{{ order.total_price | money }}</td>
    </tr>
  </tfoot>
</table><br />
```

---
## Automation - Iscrizione newsletter 
link alla mail su Jilt: [Automation - Iscrizione newsletter](https://app.jilt.com/shops/38254/campaigns/112225/emails/214261?campaignType=automation)

### Intro
```javascript
Ciao{{ customer.first_name | prepend:" " | append:", " | default: "" }}
grazie per l'iscrizione!
```

### I nostri prodotti
#### Titolo prodotto

```javascript
{% if top_products[0].id != 156 %} 
{{ top_products[0].title }} 
{% else %} Preparato per Pane 
Low Carb {% endif %}
```

#### Prezzi
{{ top_products[0].price | money }}
```javascript
{% if top_products[0].price > 0 %}{{ top_products[0].price | times:1.1 | money }}{% else %}in arrivo!{% endif %}
```