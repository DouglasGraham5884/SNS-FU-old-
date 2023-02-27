"use strict";

{

    const close = document.getElementById("close");
    const overlay = document.querySelector(".overlay");
    const post_imgs = document.querySelectorAll(".post-img");
    
    // function KeyEvent(e) {
    //     if(e.keyCode == 27) {
    //         overlay.classList.remove("show");
    //         post_imgs.forEach((img) => {
    //             img.classList.remove("zoom");
    //         });
    //     }
    // }

    // open.addEventListener("click", () => {
    //     overlay.classList.add("show");
    //     open.classList.add("hide");
    // });

    // post_img.addEventListener("click", () => {
    //     overlay.classList.add("show");
    //     open.classList.add("hide");
    // });

    // close.addEventListener("click", () => {
    //     overlay.classList.remove("show");
    //     open.classList.remove("hide");
    // });

    // 画像をクリックすると拡大表示
    post_imgs.forEach((img) => {
        img.addEventListener("click", () => {
            overlay.classList.add("show");
            img.classList.add("zoom");
        });
    });

    // ×ボタンで拡大解除
    close.addEventListener("click", () => {
        overlay.classList.remove("show");
        post_imgs.forEach((img) => {
            img.classList.remove("zoom");
        });
    });

    // 画像以外の場所クリックでも拡大解除
    overlay.addEventListener("click", () => {
        overlay.classList.remove("show");
        post_imgs.forEach((img) => {
            img.classList.remove("zoom");
        });
    });
    
    // Escキーでも拡大解除
    document.addEventListener("keydown", e => {
        if(e.keyCode == 27) {
            overlay.classList.remove("show");
            post_imgs.forEach((img) => {
                img.classList.remove("zoom");
            });
        }
    });

}