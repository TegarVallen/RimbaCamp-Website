// ============================================================
// RIMBACAMP | TASK 01, TASK 02, TASK 03
// Materi: JavaScript, DOM, Selector, Event Handling, Interaction
// ============================================================

const products = [
  {
    id: 1, name: "Tenda Dome 4 Orang", category: "tenda",
    categoryLabel: "Tenda", price: 45000, stock: 3, emoji: "⛺",
    bg: "#e5eddd", tag: "BEST SELLER",
    description: "Tenda praktis untuk perjalanan bersama teman atau keluarga."
  },
  {
    id: 2, name: "Carrier 60 Liter", category: "tas",
    categoryLabel: "Tas & Carrier", price: 30000, stock: 5, emoji: "🎒",
    bg: "#e9e4d7", tag: "FAVORIT",
    description: "Tas gunung dengan kapasitas luas untuk perjalanan beberapa hari."
  },
  {
    id: 3, name: "Sleeping Bag Polar", category: "tidur",
    categoryLabel: "Perlengkapan Tidur", price: 18000, stock: 8, emoji: "🛌",
    bg: "#e1e9ef", tag: "NYAMAN",
    description: "Menjaga tubuh tetap hangat saat beristirahat di alam terbuka."
  },
  {
    id: 4, name: "Matras Camping", category: "tidur",
    categoryLabel: "Perlengkapan Tidur", price: 12000, stock: 2, emoji: "🧘",
    bg: "#f0e6d9", tag: "RINGAN",
    description: "Alas tidur ringan dan mudah dibawa untuk kegiatan camping."
  },
  {
    id: 5, name: "Kompor Portable", category: "memasak",
    categoryLabel: "Peralatan Masak", price: 22000, stock: 4, emoji: "🔥",
    bg: "#f1e5dc", tag: "PRAKTIS",
    description: "Kompor ringkas untuk menyiapkan makanan dan minuman hangat."
  },
  {
    id: 6, name: "Headlamp Outdoor", category: "aksesoris",
    categoryLabel: "Aksesori", price: 10000, stock: 6, emoji: "🔦",
    bg: "#e5e7dc", tag: "ESSENTIAL",
    description: "Lampu kepala untuk membantu aktivitas saat kondisi minim cahaya."
  }
];

const rupiah = (value) => new Intl.NumberFormat("id-ID", {
  style: "currency", currency: "IDR", maximumFractionDigits: 0
}).format(value);

const getOrders = () => {
  try {
    return JSON.parse(localStorage.getItem("rimbacamp-orders")) || [];
  } catch (error) {
    console.error("Data pesanan tidak dapat dibaca:", error);
    return [];
  }
};

const saveOrders = (orders) => {
  localStorage.setItem("rimbacamp-orders", JSON.stringify(orders));
};

const getAvailableStock = (product) => {
  const reserved = getOrders()
    .filter(order => order.productId === product.id)
    .reduce((total, order) => total + Number(order.quantity), 0);
  return Math.max(0, product.stock - reserved);
};

// ============================================================
// TASK 01 — SELECT & CHANGE
// Mengambil elemen HTML dengan selector, lalu mengubah kontennya.
// Mengikuti contoh materi PPT halaman 11.
// ============================================================
document.addEventListener("DOMContentLoaded", () => {
  const heroTitle = document.querySelector("#hero-title");
  const heroDescription = document.querySelector("#hero-description");
  const heroBadge = document.querySelector("#hero-badge");

  // JavaScript memilih elemen berdasarkan ID dan mengubah teksnya.
  if (heroTitle) {
    heroTitle.innerHTML = 'Jelajahi Alam, <em>Tanpa Ribet</em> Menyiapkan Perlengkapan.';
  }
  if (heroDescription) {
    heroDescription.textContent =
      "Sewa perlengkapan camping berkualitas dengan proses mudah. Pilih alat, cek ketersediaan, lalu pesan secara online.";
  }
  if (heroBadge) {
    heroBadge.classList.add("is-ready");
    heroBadge.setAttribute("title", "Konten halaman berhasil diperbarui oleh JavaScript");
  }

  // Menjalankan fungsi awal setelah DOM siap.
  renderProducts();
  renderHistory();
  renderAdminDashboard();
  setMinimumDate();
});

// ============================================================
// TASK 02 — HANDLE USER EVENT
// Menangani input pencarian, filter, navigasi, riwayat, dan admin.
// Mengikuti materi Event Handling pada PPT halaman 12.
// ============================================================
const searchInput = document.querySelector("#search-input");
const categoryFilter = document.querySelector("#category-filter");
const sortFilter = document.querySelector("#sort-filter");
const productGrid = document.querySelector("#product-grid");
const emptyState = document.querySelector("#empty-state");
const catalogCount = document.querySelector("#catalog-count");

function renderProducts() {
  if (!productGrid) return;

  const keyword = (searchInput?.value || "").toLowerCase().trim();
  const category = categoryFilter?.value || "semua";
  const sort = sortFilter?.value || "default";

  let filteredProducts = products.filter(product => {
    const matchesKeyword =
      product.name.toLowerCase().includes(keyword) ||
      product.categoryLabel.toLowerCase().includes(keyword) ||
      product.description.toLowerCase().includes(keyword);
    const matchesCategory = category === "semua" || product.category === category;
    return matchesKeyword && matchesCategory;
  });

  if (sort === "low") filteredProducts.sort((a, b) => a.price - b.price);
  if (sort === "high") filteredProducts.sort((a, b) => b.price - a.price);
  if (sort === "name") filteredProducts.sort((a, b) => a.name.localeCompare(b.name));

  catalogCount.textContent = `${filteredProducts.length} perlengkapan`;
  emptyState.classList.toggle("hidden", filteredProducts.length !== 0);

  productGrid.innerHTML = filteredProducts.map(product => {
    const available = getAvailableStock(product);
    return `
      <article class="product-card">
        <div class="product-art" style="--art-bg: ${product.bg}">
          <span class="product-label">${product.tag}</span>
          <span class="stock-label ${available === 0 ? "out" : ""}">
            ${available > 0 ? `${available} tersedia` : "Stok habis"}
          </span>
          <span class="product-emoji" aria-hidden="true">${product.emoji}</span>
        </div>
        <div class="product-info">
          <span class="product-category">${product.categoryLabel}</span>
          <h3>${product.name}</h3>
          <p>${product.description}</p>
          <div class="product-bottom">
            <div class="product-price">
              <strong>${rupiah(product.price)}</strong>
              <small>per hari / unit</small>
            </div>
            <button class="rent-button" type="button"
              data-product-id="${product.id}" ${available === 0 ? "disabled" : ""}>
              ${available === 0 ? "Stok Habis" : "Sewa Sekarang"}
            </button>
          </div>
        </div>
      </article>
    `;
  }).join("");
}

// Event input: hasil pencarian berubah saat pengguna mengetik.
searchInput?.addEventListener("input", renderProducts);

// Event change: katalog berubah ketika kategori atau urutan dipilih.
categoryFilter?.addEventListener("change", renderProducts);
sortFilter?.addEventListener("change", renderProducts);

// Event click pada tombol reset pencarian.
document.querySelector("#reset-filter")?.addEventListener("click", () => {
  searchInput.value = "";
  categoryFilter.value = "semua";
  sortFilter.value = "default";
  renderProducts();
});

// Event click pada menu mobile.
const menuToggle = document.querySelector("#menu-toggle");
const mainNav = document.querySelector("#main-nav");

menuToggle?.addEventListener("click", () => {
  const isOpen = mainNav.classList.toggle("open");
  menuToggle.setAttribute("aria-expanded", String(isOpen));
});

mainNav?.querySelectorAll("a, button").forEach(item => {
  item.addEventListener("click", () => {
    mainNav.classList.remove("open");
    menuToggle?.setAttribute("aria-expanded", "false");
  });
});

// Event click untuk menampilkan riwayat pesanan.
const historySection = document.querySelector("#history-section");
document.querySelector("#history-button")?.addEventListener("click", () => {
  historySection.classList.toggle("hidden");
  document.querySelector("#admin-section").classList.add("hidden");
  renderHistory();
  historySection.scrollIntoView({ behavior: "smooth", block: "start" });
});
document.querySelector("#close-history")?.addEventListener("click", () => {
  historySection.classList.add("hidden");
});

// Event click untuk membuka dashboard admin sederhana.
const adminSection = document.querySelector("#admin-section");
document.querySelector("#admin-button")?.addEventListener("click", () => {
  adminSection.classList.toggle("hidden");
  historySection.classList.add("hidden");
  renderAdminDashboard();
  adminSection.scrollIntoView({ behavior: "smooth", block: "start" });
});
document.querySelector("#close-admin")?.addEventListener("click", () => {
  adminSection.classList.add("hidden");
});

// ============================================================
// TASK 03 — BUILD ONE COMPLETE INTERACTION
// Alur: klik Sewa Sekarang -> pilih alat -> isi form -> hitung total
// -> submit -> simpan pesanan -> tampilkan konfirmasi dan riwayat.
// Mengikuti contoh alur interaksi pada PPT halaman 13.
// ============================================================
const bookingModal = document.querySelector("#booking-modal");
const bookingForm = document.querySelector("#booking-form");
const selectedProductBox = document.querySelector("#selected-product");
const productIdInput = document.querySelector("#booking-product-id");
const durationInput = document.querySelector("#duration");
const quantityInput = document.querySelector("#quantity");
const startDateInput = document.querySelector("#start-date");
const estimatedTotal = document.querySelector("#estimated-total");
const availabilityHint = document.querySelector("#availability-hint");

let selectedProduct = null;

function setMinimumDate() {
  if (!startDateInput) return;
  const today = new Date();
  const localDate = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
    .toISOString().split("T")[0];
  startDateInput.min = localDate;
  startDateInput.value = localDate;
}

function updateBookingEstimate() {
  if (!selectedProduct) return;
  const days = Math.max(1, Number(durationInput.value) || 1);
  const quantity = Math.max(1, Number(quantityInput.value) || 1);
  const available = getAvailableStock(selectedProduct);

  estimatedTotal.textContent = rupiah(selectedProduct.price * days * quantity);
  quantityInput.max = String(Math.max(1, available));
  availabilityHint.textContent = `Stok tersedia saat ini: ${available} unit. Maksimal 30 hari penyewaan.`;

  if (quantity > available) {
    availabilityHint.textContent = `Jumlah melebihi stok. Saat ini hanya tersedia ${available} unit.`;
    availabilityHint.style.color = "#b35b4d";
  } else {
    availabilityHint.style.color = "#9a8060";
  }
}

// Event click pada katalog. Event delegation menangani tombol hasil render.
productGrid?.addEventListener("click", (event) => {
  const rentButton = event.target.closest("[data-product-id]");
  if (!rentButton) return;

  const productId = Number(rentButton.dataset.productId);
  selectedProduct = products.find(product => product.id === productId);
  if (!selectedProduct) return;

  const available = getAvailableStock(selectedProduct);
  if (available < 1) {
    showToast("Maaf, stok perlengkapan ini sedang habis.");
    return;
  }

  productIdInput.value = String(selectedProduct.id);
  selectedProductBox.innerHTML = `
    <span class="selected-emoji">${selectedProduct.emoji}</span>
    <div><strong>${selectedProduct.name}</strong>
    <small>${rupiah(selectedProduct.price)} / hari / unit · ${available} unit tersedia</small></div>
  `;

  quantityInput.value = "1";
  quantityInput.max = String(available);
  durationInput.value = "1";
  updateBookingEstimate();

  bookingModal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.querySelector("#customer-name").focus();
});

function closeBookingModal() {
  bookingModal.classList.add("hidden");
  document.body.style.overflow = "";
}
document.querySelector("#close-modal")?.addEventListener("click", closeBookingModal);
bookingModal?.addEventListener("click", (event) => {
  if (event.target === bookingModal) closeBookingModal();
});
document.addEventListener("keydown", (event) => {
  if (event.key === "Escape" && !bookingModal.classList.contains("hidden")) {
    closeBookingModal();
  }
});

// Event input: estimasi biaya diperbarui saat durasi/jumlah berubah.
durationInput?.addEventListener("input", updateBookingEstimate);
quantityInput?.addEventListener("input", updateBookingEstimate);

// Event submit: validasi dan simpan pesanan.
bookingForm?.addEventListener("submit", (event) => {
  event.preventDefault();

  const product = products.find(item => item.id === Number(productIdInput.value));
  if (!product) {
    showToast("Pilih perlengkapan terlebih dahulu.");
    return;
  }

  const customerName = document.querySelector("#customer-name").value.trim();
  const customerPhone = document.querySelector("#customer-phone").value.trim();
  const startDate = startDateInput.value;
  const duration = Number(durationInput.value);
  const quantity = Number(quantityInput.value);
  const available = getAvailableStock(product);

  if (!customerName || !customerPhone || !startDate || duration < 1 || duration > 30 || quantity < 1) {
    showToast("Periksa kembali data pemesanan kamu.");
    return;
  }
  if (quantity > available) {
    showToast(`Stok tidak cukup. Tersedia ${available} unit.`);
    updateBookingEstimate();
    return;
  }

  const total = product.price * duration * quantity;
  const order = {
    code: `RC${Date.now().toString().slice(-7)}`,
    productId: product.id,
    productName: product.name,
    emoji: product.emoji,
    customerName,
    customerPhone,
    startDate,
    duration,
    quantity,
    total,
    status: "Menunggu konfirmasi",
    createdAt: new Date().toISOString()
  };

  const orders = getOrders();
  orders.unshift(order);
  saveOrders(orders);

  closeBookingModal();
  bookingForm.reset();
  setMinimumDate();
  renderProducts();
  renderHistory();
  renderAdminDashboard();

  showToast(`Pesanan ${order.code} berhasil dibuat. Total ${rupiah(total)}. Cek bagian Riwayat Pesanan.`);
});

// Menampilkan daftar riwayat pesanan.
function renderHistory() {
  const historyList = document.querySelector("#history-list");
  if (!historyList) return;
  const orders = getOrders();

  if (orders.length === 0) {
    historyList.innerHTML = '<div class="no-history">Belum ada pesanan. Silakan pilih perlengkapan dari katalog RimbaCamp.</div>';
    return;
  }

  historyList.innerHTML = orders.map(order => `
    <article class="history-item">
      <div>
        <h3>${order.emoji} ${order.productName}</h3>
        <p>Kode: ${order.code} · ${order.customerName}<br>
        Mulai: ${order.startDate} · ${order.duration} hari · ${order.quantity} unit</p>
      </div>
      <div class="history-price">
        <strong>${rupiah(order.total)}</strong>
        <span class="status-pill">${order.status}</span>
      </div>
    </article>
  `).join("");
}

// Menampilkan ringkasan pesanan pada dashboard admin.
function renderAdminDashboard() {
  const orders = getOrders();
  const totalValue = orders.reduce((sum, order) => sum + Number(order.total), 0);
  const pending = orders.filter(order => order.status === "Menunggu konfirmasi").length;

  document.querySelector("#admin-total-orders").textContent = orders.length;
  document.querySelector("#admin-total-value").textContent = rupiah(totalValue);
  document.querySelector("#admin-pending").textContent = pending;

  const tableBody = document.querySelector("#admin-table-body");
  if (!tableBody) return;

  if (orders.length === 0) {
    tableBody.innerHTML = '<tr><td colspan="6" class="table-empty">Belum ada pesanan.</td></tr>';
    return;
  }

  tableBody.innerHTML = orders.map(order => `
    <tr>
      <td>${order.code}</td>
      <td>${order.customerName}</td>
      <td>${order.productName} (${order.quantity})</td>
      <td>${order.startDate}</td>
      <td>${rupiah(order.total)}</td>
      <td>${order.status}</td>
    </tr>
  `).join("");
}

// Pesan singkat untuk memberi respons setelah aksi pengguna.
let toastTimer;
function showToast(message) {
  const toast = document.querySelector("#toast");
  toast.textContent = message;
  toast.classList.remove("hidden");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.add("hidden"), 4500);
}
