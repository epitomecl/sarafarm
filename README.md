# sarafarm

Sarafarm project (farm data to government, vegetarian diet program, vegetarian database).

Over the time this project changes a lot. Therefore some pages cannot be navigated directly.

## Farm data exchange

<p align="justify">
The target is to bring the farmer into cooperation as farmer or validator with Ministry of Agriculture. 
Each farmer upload there data for forecast crop production. 
Each validator take care of delivered farmer data. 
After validator validate the data, the farmer get paid in Sarafarm token.
The data requestors can request farmer data over blockchain (smart contract).
All farmer data are stored on blockchain (smart contract).
Every farmer has their own wallet address.
The solution is based on Ethereum blockchain.
The sarafarm token are later useful for buying farmer relevant product as such as fertilizers, seeds and agricultural implements.
</p>

<p align="justify">
The login call is /login.php, there are over 100 virtuell farmers in the database included.
The xxx should be changed into 001 - 120 for testing.
</p>

```
user: 00000xxx@sarafarm.io
pass: 1234
```

## Diet program

<p align="justify">
This approach comes with as combination of gamification and vegetarian health food consulting.
The user choose together with an virtual consultant "Sara" a selection of daily food.
After user choosed for breakfast, lunch and dinner a selection, Sara will check user's preference.
In a summary Sara will recommand and guide the user to healthy way of life.
</p>
<p align="justify">
Each selection is based on simple self made kitchen creation. Each ingedients are calculated for one meal.
In the section vegan weekly plan the user can inspire themself with an diversified menu plan with recipes for self cooking.
The food gallery are produced based on food database of diet studies and hold an collection of at the moment of 137 out of 8000 nutriments. 
</p>
<p align="justify">
Here is no special login at the moment. At this stage is was to early to setup a vegetarian user community.
</p>

## vegetarian database

<p align="justify">
Our vegetarian database contains adapted nutrition data from "Food and Nutrient Database for Dietary Studies".
We have added several vegan categories and a special Sara food star rating.
</p>

## API Overview

<p align="justify">
Because of restriction of webpage hoster is the folder named with api.sarafarm.io and called from root directory. 
The test api page is here not included. Therefore a direct call /api.sarafarm.io/ will deliver a blank page.
</p>

### Profile

<p align="justify">
Update the farmer profile or fill up a farmer profile.
For updating we used POST.  Based on profileId, we update firstName, lastName, alias (user name), email, about (some lines of self description), 
address, file (image file name) and imageData. For show up a profile we are calling the current profile by user ID.
</p>

### Contact

<p align="justify">
Stores the contact information in database and validate the email address.
</p>

### FoodImage

<p align="justify">
Updates the food image by POST call.
</p>

### Vegetarian

<p align="justify">
Update the data set for specified nutrition. The column param "vegan", "lacto_vegan", "ovo_vegan", "pescatarian" describes
the used table column for storing the checked category.
</p>

### FoodMenu

<p align="justify">
Delivered a set of food data for showing a list of food.
</p>

### FoodInfo

<p align="justify">
Delivered specific information about current food selection based on given food code.
</p>

### SummaryChart

<p align="justify">
Based on preselected food for breakfast, lunch, dinner and snacks the service calculate a summary chart as json-data set. 
</p>