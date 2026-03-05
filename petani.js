const form = document.getElementById("formPetani");
const tabel = document.getElementById("tabelPetani");

let dataPetani = [];

form.addEventListener("submit", function (e) {
  e.preventDefault();

  let nama = document.getElementById("nama").value;
  let nik = document.getElementById("nik").value;
  let alamat = document.getElementById("alamat").value;
  let telp = document.getElementById("telp").value;
  let lahan = document.getElementById("lahan").value;
  let editIndex = document.getElementById("editIndex").value;

  let jkRadio = document.querySelector('input[name="jk"]:checked');

  if (!nama.match(/^[a-zA-Z\s]+$/)) {
    alert("Nama hanya boleh huruf");
    return;
  }

  if (!nik.match(/^\d{16}$/)) {
    alert("NIK harus 16 digit angka");
    return;
  }

  if (!telp.match(/^\d+$/)) {
    alert("No telp hanya angka");
    return;
  }

  if (!jkRadio) {
    alert("Pilih jenis kelamin");
    return;
  }

  let jk = jkRadio.value;

  let petani = {
    nama,
    nik,
    alamat,
    telp,
    jk,
    lahan,
  };

  if (editIndex === "") {
    dataPetani.push(petani);
  } else {
    dataPetani[editIndex] = petani;
  }

  form.reset();
  document.getElementById("editIndex").value = "";

  tampilkanData();
});

function tampilkanData() {
  tabel.innerHTML = "";

  dataPetani.forEach((p, index) => {
    tabel.innerHTML += `
<tr>

<td>${p.nama}</td>
<td>${p.nik}</td>
<td>${p.alamat}</td>
<td>${p.telp}</td>
<td>${p.jk}</td>
<td>${p.lahan} Ha</td>

<td>
<button class="edit" onclick="editData(${index})">Edit</button>
<button class="delete" onclick="hapusData(${index})">Hapus</button>
</td>

</tr>
`;
  });
}

function editData(index) {
  let p = dataPetani[index];

  document.getElementById("nama").value = p.nama;
  document.getElementById("nik").value = p.nik;
  document.getElementById("alamat").value = p.alamat;
  document.getElementById("telp").value = p.telp;
  document.getElementById("lahan").value = p.lahan;

  document.querySelector(`input[name="jk"][value="${p.jk}"]`).checked = true;

  document.getElementById("editIndex").value = index;
}

function hapusData(index) {
  if (confirm("Yakin ingin menghapus data?")) {
    dataPetani.splice(index, 1);
    tampilkanData();
  }
}
