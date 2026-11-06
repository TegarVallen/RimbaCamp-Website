// ============================================================
// WEEK 4 - TASK 03: HANDLE API STATE
// Memberi feedback ke pengguna saat API sedang diproses (loading),
// berhasil (success), atau gagal (error).
// Alur: REQUEST -> LOADING -> RESPONSE -> SUCCESS / ERROR
// ============================================================

const statusBox = document.querySelector("#status");
const jumlahBox = document.querySelector("#jumlah");

function tampilkanStatus(pesan, tipe = "info", denganTombolUlang = false) {
  statusBox.hidden = false;
  statusBox.className = "status " + tipe;
  statusBox.innerHTML = tipe === "info"
    ? `<span class="spinner" aria-hidden="true"></span>${pesan}`
    : pesan;

  if (denganTombolUlang) {
    const tombol = document.createElement("button");
    tombol.textContent = "Coba lagi";
    tombol.addEventListener("click", loadAlat);
    statusBox.append(tombol);
  }
}

function sembunyikanStatus() {
  statusBox.hidden = true;
}

// Alur lengkap: loading -> panggil API -> success atau error
async function loadAlat() {
  daftarAlat.innerHTML = "";
  jumlahBox.hidden = true;
  tampilkanStatus("Mengambil data alat...");            // LOADING

  try {
    // jeda singkat supaya loading terlihat saat demo video
    await new Promise(resolve => setTimeout(resolve, 800));

    semuaAlat = await ambilDataAlat();                  // dari api.js (Task 01)
    isiFilterKategori(semuaAlat);                       // dari render.js (Task 02)
    sembunyikanStatus();                                // SUCCESS
    tampilkanAlat();
  } catch (error) {                                     // ERROR
    console.error(error);
    tampilkanStatus(
      "Data alat tidak bisa dimuat. Periksa koneksi atau server, lalu coba lagi.",
      "error",
      true
    );
  }
}
