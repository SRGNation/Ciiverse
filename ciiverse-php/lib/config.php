<?php
// Database settings.
const DB_SERVER = 'localhost'; // The hostname of your db server. Required.
const DB_USERNAME = 'root'; // The username you'll use to access the database. Required.
const DB_PASSWORD = ''; // The password you'll use to access the database. Optional.
const DB_DATABASE = 'ciiverse'; // The name of the database. Required.

// Cloudinary settings. All of this is optional.
const CLOUDINARY_NAME = null; // The cloud name to your Cloudinary account.
const CLOUDINARY_KEY = null; // The key you'll use to access the Cloudinary.
const CLOUDINARY_PRESET = null; // The unsigned upload preset of your Cloudinary account.

// ReCAPTCHA settings.
const RECAPTCHA_KEY = null; // The public key for ReCAPTCHA. Optional.
const RECAPTCHA_SECRET = null; // The private key for ReCAPTCHA. Optional.

// Other settings.
const DISCORD_WEBHOOK = null; // This is for if you want to use the post_to_discord() function for Discord Webhooks. Optional.
const AUTO_IMAGE_PERMISSIONS = false; // If set to true, it will give the user image uploading permissions as soon as they create their account. Required.
const TIMEZONE = 'America/New_York'; // The timezone used by the site. Required.

// Memo settings.
const MEMO_TITLE = 'Ciiverse'; // The title of your memo. Required.
const MEMO_CONTENT = 'Ciiverse is an open source Miiverse clone created by SRGNation. I wouldn\'t reccomend using it for your 431243125th Miiverse clone rehost, because it is still extremely unsecure. However, this version adds a few more features and fixes up a few things to make it less confusing. Kind of inspired by PF2M releasing a slightly fixed up version of Openverse, and also for Ciiverse\'s 3rd anniversary of existing...<br><br>Also, if you are concerned as to why I continue to support this. Well, first of all, no. I do not intend to bring Miiverse clones back. Ciiverse and its original database will continue to not see the light of day, thankfully.<br><br>The reason I intend to rewrite this all boils down to me being bored and having nothing to do. Also because if I really want to, I can rewrite and improve the entirety of Ciivere. Which is what i\'m doing now.'; // The content of your memo. Required.