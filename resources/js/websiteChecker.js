const axios = require('axios');
const sslChecker = require('ssl-checker');
const dns = require('dns');

// Fungsi untuk melakukan pengecekan website
async function checkWebsite(url) {
    const result = {
        url: url,
        status: 'Unknown',
        ipAddress: 'Unknown',
        sslStatus: 'Unknown',
        sslExpiryDate: 'Unknown',
        responseTime: 'Unknown',
        checkedAt: new Date().toLocaleString(),
    };

    try {
        // Cek waktu respons dan status HTTP
        const start = new Date().getTime();
        const response = await axios.get(url);
        const end = new Date().getTime();
        result.status = response.status === 200 ? 'Up' : 'Down';
        result.responseTime = `${end - start} ms`;

        // Cek alamat IP
        const ip = await new Promise((resolve, reject) => {
            dns.lookup(url.replace('https://', '').replace('http://', ''), (err, address) => {
                if (err) reject(err);
                resolve(address);
            });
        });
        result.ipAddress = ip;

        // Cek SSL Status
        const sslDetails = await sslChecker(url);
        result.sslStatus = sslDetails.valid ? 'Valid' : 'Invalid';
        result.sslExpiryDate = sslDetails.daysRemaining > 0 ? sslDetails.validTo : 'Expired';
    } catch (error) {
        result.status = 'Down';
    }

    return result;
}

// Menerima input dari Laravel dan melakukan pengecekan
(async () => {
    const websites = JSON.parse(process.argv[2]); // Ambil input URL dari Laravel
    const results = await Promise.all(websites.map(url => checkWebsite(url)));
    console.log(JSON.stringify(results)); // Kirim hasil pengecekan kembali ke Laravel
})();
