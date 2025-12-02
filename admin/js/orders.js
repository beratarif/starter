const tbody = document.getElementById("ordersTableBody");

async function renderOrders() {
  const yanit = await fetch(`../backend/siparis.php?islem=getir`);
  const yanitJson = await yanit.json();

  tbody.innerHTML = "";

  yanitJson.forEach((f) => {
    tbody.innerHTML += `
      <tr>
        <td style="cursor:pointer;" onclick=window.location.href='../admin/siparis_detay.php' >${
          f.siparis_id
        }</td>
        <td>${f.ad_soyad}</td>
        <td>${f.tutar} ₺</td>
        
        <td>
        <span class="badge ${
          f.durum === "Bekliyor"
            ? "bg-warning text-dark"
            : f.durum === "Kargolandı"
            ? "bg-info text-dark"
            : f.durum === "İptal Edildi"
            ? "bg-danger text-white"
            : "bg-success"
        }">
          ${f.durum}
          </span>
          </td>
          
          <td>
            <select class="btn btn-secondary" onchange="updateStatus(${
              f.siparis_id
            }, this.value)">
              <option value="Bekliyor"   ${
                f.durum === "Bekliyor" ? "selected" : ""
              }>Bekliyor</option>
              <option value="Kargolandı" ${
                f.durum === "Kargolandı" ? "selected" : ""
              }>Kargolandı</option>
              <option value="Tamamlandı" ${
                f.durum === "Tamamlandı" ? "selected" : ""
              }>Tamamlandı</option>
              <option value="İptal edildi" ${
                f.durum === "İptal Edildi" ? "selected" : ""
              }>İptal Edildi</option>
            </select>
            <button class="btn detayBtn btn-primary">Detay</button>
          </td>
        

      </tr>
    `;
  });
  // tr.querySelector(".detayBtn").onclick = () => {
  //   document.getElementById("detay").innerHTML = `
  //               <p><strong>Müşteri:</strong> ${f.ad_soyad}</p>
  //               <p><strong>Ürün:</strong> ${f.urun_adi}</p>
  //               <p><strong>Adet:</strong> ${f.adet}</p>
  //               <p><strong>Toplam:</strong> ${f.tutar}₺</p>
  //               <p><strong>Ödeme Yöntemi:</strong> ${f.odeme_yontemi}</p>
  //               <p><strong>Durum:</strong> ${f.durum}</p>
  //           `;
  //   new bootstrap.Modal(document.getElementById("detayModal")).show();
  // };
}

async function updateStatus(index, newStatus) {
  await fetch(
    `../backend/siparis.php?islem=durum_degistir&siparis_id=${index}&yeni_durum=${newStatus}`
  );

  renderOrders();
}
renderOrders();
