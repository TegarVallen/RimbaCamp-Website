// ============================================================
// WEEK 4 - TASK 01: CONNECT TO API
// Menghubungkan website dengan API yang menyediakan data alat camping.
// Alur: JavaScript -> fetch() -> REST API -> JSON
// ============================================================

// Endpoint API. Tambahkan ?error=1 di URL untuk mensimulasikan API gagal (demo).
const params = new URLSearchParams(window.location.search);
const API_URL = params.get("error") ? "data/tidak-ada.json" : "data/alat.json";

// Mengirim request ke API lalu mengembalikan array alat (array of objects).
async function ambilDataAlat() {
  const response = await fetch(API_URL);                // kirim request (GET)

  if (!response.ok) {                                   // cek response dari server
    throw new Error("Gagal mengambil data (status " + response.status + ")");
  }

  const data = await response.json();                   // JSON -> object JavaScript
  return data.alat;
}
