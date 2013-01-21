Facebook Photos
===============

Overview
--------
This module is meant to help you display your Facebook photos from a public Facebook account. I developed this for my personal sites and business sites to help with my goal of _"Information Cascade."_

Installation
------------
1. Just place all of the contents in the _/system/expressionengine/third_party/fbphotos_
2. Then log into your admin area and click on "Add Ons"->"Modules"
3. Find "Facebook Photos" in the list and click on _install_
4. Then from the Module's list click on the "Facebook Photos" link

Setup
-----
1. Insert you Facebook ID into the text area. [Click Here](http://findmyfacebookid.com) to find your Facebook ID using your Facebook URL.
2. After clicking "Submit" you will then be shown the names of your public photo albums.
3. Simply check the box of the albums that you want to pull images from.
4. After clicking "Submit" you will now see that your selected albums are seperated out from your available albums

Get Photos Syntax
-----------------
The tag pair that is used is called "get_photos" and is used like so:

	{exp:fbphotos:get_photos limit="10" size="large" thumbnail="small"}
  	   <a href="{source}" title="{name}"><img src="{thumbnail}" /></a>
	{/exp:fbphotos:get_photos}

Parameters
----------
- **limit** : (Optional) This will default to 50 if not number is given, but allows you to give your own number and limit the amount of photos returned.
- **size** : (Optional) This determines what size image you want returned from the array of images that the Facebook Graph returns. By default it will use "medium".  
_Available Sizes:_ xsmall, small, medium, large, xlarge

- **thumbnail** : (Optional) You only need to set this if you want to have a thumbnail at a different size that can link to a larger size.  
_Available Sizes:_ xsmall, small, medium, large, xlarge

FAQs
----
**Q: Why does it take so long for my images to load?**  
**A:** Remember to limit how many are returned and switch up the sizes returned as well.

**Q: Is there a way to cache the photos so that I don't have to have them load every time.?**  
**A:** Not at this time, I made this so that my clients wouldn't have to go to their site to update their photos. If there is enought demand for cacheing I will setup a way to select your albums and then since the photos from Facebook into your database so that you will only have to call Facebook on sync only.

Thank You
---------
Finally, thank you for using this add on - please feel free to post an issue with bugs or additional functionalities you would like to see. Also, I don't have a tons of experience with the Facebook Graph so if you see any ways to optimize this code, feel free to fork it and then send a push request.
