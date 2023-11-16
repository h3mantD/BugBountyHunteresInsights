"use strict";

import puppeteer from "puppeteer";
import yargs from "yargs";
import { hideBin } from "yargs/helpers";
import { MongoClient } from "mongodb";
import dotenv from "dotenv";
dotenv.config();

// Database connection and collection setup
const uri = process.env.DB_URI;
const db_name = process.env.DB_NAME;
const client = new MongoClient(uri);
const database = client.db(db_name);
const pivot_collection = database.collection("user_platforms");

// Command line arguments setup
const argv = yargs(hideBin(process.argv))
    .option("username", {
        alias: "u",
        describe: "The Username whose stats to be fetched",
        demandOption: true,
    })
    .option("platform", {
        alias: "p",
        describe: "The bug bounty platform from which the stats to be fetched",
        choices: ["hackerone", "bugcrowd", "yeswehack"],
        demandOption: true,
    })
    .option("user-id", {
        describe: "user id from the db",
    })
    .help().argv;

// Helper function to delay execution
const delay = (milliseconds) =>
    new Promise((resolve) => setTimeout(resolve, milliseconds));

// Function to connect to the database and store stats
async function connectToDbAndStoreStats(username, platformName, stats) {
    try {
        await client.connect();
        await storeStats(username, platformName, stats);
    } catch (e) {
        console.error(e);
    } finally {
        await client.close();
    }
}

// Function to store stats in the database
async function storeStats(username, platform, stats) {
    const filter = { username, platform, verified: true };
    const options = { upsert: true };
    // get current timestamp
    let last_updated_on = new Date()
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
    const updateDoc = {
        $set: { stats, last_updated_on },
    };

    await pivot_collection.updateOne(filter, updateDoc, options);
}

let stat = null;

// Function to get stats from the bug bounty platform
const getBBStat = async (username, platform) => {
    const browser = await puppeteer.launch({
        // executablePath: '/usr/bin/chromium-browser',
        headless: true,
        args: [
            "--disable-gpu",
            "--disable-dev-shm-usage",
            "--disable-setuid-sandbox",
            "--no-first-run",
            "--no-sandbox",
            "--no-zygote",
            "--deterministic-fetch",
            "--disable-features=IsolateOrigins",
            "--disable-site-isolation-trials",
        ],
    });

    const page = await browser.newPage();

    await page.setRequestInterception(true);
    await page.setDefaultNavigationTimeout(0);

    page.on("request", (request) => {
        request.continue();
    });

    page.on("response", async (response) => {
        if (
            response.url().endsWith("graphql") ||
            response.url().endsWith(".json")
        ) {
            parseAndStoreResponse(await response.json(), platform);
        }

        delay(3000);
    });

    await page.goto(getPlatformUrl(platform, username), {
        waitUntil: "networkidle2",
    });

    await page.waitForTimeout(5000);

    browser.close();
};

// Function to construct the platform URL
function getPlatformUrl(platform, username) {
    const baseUrls = {
        hackerone: "https://hackerone.com/",
        bugcrowd: "https://bugcrowd.com/",
        yeswehack: "https://api.yeswehack.com/hunters/",
    };

    return baseUrls[platform] + username;
}

// Function to parse the response and store the stats
function parseAndStoreResponse(response, platform) {
    let tempResponse = null;

    if (platform === "hackerone" && response.data?.user?.statistics_snapshot) {
        tempResponse = response.data.user.statistics_snapshot;
    }

    if (platform === "bugcrowd" && response.statistics) {
        tempResponse = response.statistics;
    }

    if (platform === "yeswehack") {
        tempResponse = response;
    }

    if (tempResponse && tempResponse !== stat) {
        stat = tempResponse;
        if (stat) {
            console.log(stat);
            connectToDbAndStoreStats(user, platform, stat).catch(console.error);
        }
    }
}

let user = argv.username;
let platform = argv.platform;
getBBStat(user, platform);
