import line5 from "./line-5.svg";
import line6 from "./line-6.svg";
import "./login_style.css";

export const Box = (): JSX.Element => {
  return (
    <div className="box">
      <div className="masuk">
        <div className="rectangle" />
        <div className="text-wrapper">Masuk</div>
        <p className="div">Masukan kredensial untuk Anda untuk melanjutkan</p>
        <img className="line" alt="Line" src={line5} />
        <img className="img" alt="Line" src={line6} />
        <div className="text-wrapper-2">Email *</div>
        <div className="text-wrapper-3">Password *</div>
        <div className="rectangle-2" />
        <div className="rectangle-3" />
        <div className="text-wrapper-4">Masukkan Password. . .</div>
        <div className="rectangle-4" />
        <div className="text-wrapper-5">Masuk</div>
        <div className="text-wrapper-6">Belum Punya Akun?</div>
        <div className="text-wrapper-7">Daftar sini</div>
        <div className="text-wrapper-8">Masukkan Email. . .</div>
      </div>
    </div>
  );
};

</body>
</html>