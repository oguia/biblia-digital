const puppeteer = require('puppeteer');
(async () => {
  const browser = await puppeteer.launch({ args: ['--no-sandbox', '--disable-setuid-sandbox'] });
  const page = await browser.newPage();
  page.on('console', msg => console.log('BROWSER LOG:', msg.text()));
  page.on('pageerror', err => console.log('BROWSER ERROR:', err.message));

  await page.evaluateOnNewDocument(() => {
    localStorage.setItem('vouzelar_user', JSON.stringify({
      id: 1,
      name: 'Admin User',
      email: 'admin@vouzelar.com',
      role: 'admin',
      plan: 'Família'
    }));
    // Note: User said it worked, but blank. I will navigate to index.html using file:// protocol like if it was opened on hostinger and not compiled well, OR just the fact it might be a cache issue.
  });

  await page.goto('https://vouzelar.maisdeus.com/#/dashboard');
  await new Promise(r => setTimeout(r, 2000));

  // Try taking a screenshot
  await page.screenshot({ path: '/app/vouzelar/client/public/debug.png' });
  const bodyText = await page.$eval('body', el => el.innerText);
  console.log('Production Body Text:', bodyText.substring(0, 100));

  await browser.close();
  process.exit(0);
})();
