// Media Uploader - Banner and Video Upload Handler
document.addEventListener("DOMContentLoaded", function () {
    // ==================== BANNER UPLOAD ====================

    // 1. Upload from Device
    const uploadBannerBtn = document.getElementById("uploadBannerBtn");
    const bannerFileInput = document.getElementById("bannerFileInput");

    if (uploadBannerBtn && bannerFileInput) {
        uploadBannerBtn.addEventListener("click", () => {
            bannerFileInput.click();
        });

        bannerFileInput.addEventListener("change", (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith("image/")) {
                displayBannerPreview(URL.createObjectURL(file));
                document.getElementById("bannerSource").value = "device";
            } else {
                alert(
                    document.querySelector('[data-msg="invalid-image"]')
                        ?.dataset.msg || "Please select a valid image file."
                );
            }
        });
    }

    // 2. Choose from Library
    const libraryBannerBtn = document.getElementById("libraryBannerBtn");
    const libraryBannerModal = new bootstrap.Modal(
        document.getElementById("libraryBannerModal")
    );

    if (libraryBannerBtn) {
        libraryBannerBtn.addEventListener("click", () => {
            libraryBannerModal.show();
            loadMediaLibrary("image", "bannerLibraryContent");
        });
    }

    // 3. From URL
    const urlBannerBtn = document.getElementById("urlBannerBtn");
    const urlBannerModal = new bootstrap.Modal(
        document.getElementById("urlBannerModal")
    );
    const submitBannerUrl = document.getElementById("submitBannerUrl");
    const bannerUrlInput = document.getElementById("bannerUrlInput");

    if (urlBannerBtn) {
        urlBannerBtn.addEventListener("click", () => {
            urlBannerModal.show();
        });
    }

    if (submitBannerUrl) {
        submitBannerUrl.addEventListener("click", async () => {
            const url = bannerUrlInput.value.trim();
            if (!url) return;

            // Validate URL
            if (!isValidImageUrl(url)) {
                alert("Invalid image URL. Please enter a valid image URL.");
                return;
            }

            // Check if URL is accessible
            // We will not validate the URL on the client-side to avoid CORS issues.
            // The browser can display the image, and the server will handle fetching and validation upon form submission.
            displayBannerPreview(url);
            document.getElementById("bannerSource").value = "url";
            document.getElementById("bannerUrl").value = url;
            urlBannerModal.hide();
            bannerUrlInput.value = "";
        });
    }

    // 4. Paste from Clipboard
    const pasteBannerBtn = document.getElementById("pasteBannerBtn");

    if (pasteBannerBtn) {
        pasteBannerBtn.addEventListener("click", async () => {
            try {
                const clipboardItems = await navigator.clipboard.read();
                let imageFound = false;

                for (const item of clipboardItems) {
                    for (const type of item.types) {
                        if (type.startsWith("image/")) {
                            const blob = await item.getType(type);
                            const url = URL.createObjectURL(blob);
                            displayBannerPreview(url);
                            document.getElementById("bannerSource").value =
                                "clipboard";

                            // Convert blob to file and set to input
                            const file = new File(
                                [blob],
                                "clipboard-image.png",
                                { type: blob.type }
                            );
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            bannerFileInput.files = dataTransfer.files;

                            imageFound = true;
                            break;
                        }
                    }
                    if (imageFound) break;
                }

                if (!imageFound) {
                    alert(
                        "No image found in clipboard. Please copy an image first."
                    );
                }
            } catch (error) {
                alert("Error accessing clipboard: " + error.message);
            }
        });
    }

    // ==================== VIDEO UPLOAD ====================

    // 1. Upload from Device
    const uploadVideoBtn = document.getElementById("uploadVideoBtn");
    const videoFileInput = document.getElementById("videoFileInput");

    if (uploadVideoBtn && videoFileInput) {
        uploadVideoBtn.addEventListener("click", () => {
            videoFileInput.click();
        });

        videoFileInput.addEventListener("change", (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith("video/")) {
                displayVideoPreview(URL.createObjectURL(file));
                document.getElementById("videoSource").value = "device";
            } else {
                alert("Please select a valid video file.");
            }
        });
    }

    // 2. Choose from Library
    const libraryVideoBtn = document.getElementById("libraryVideoBtn");
    const libraryVideoModal = new bootstrap.Modal(
        document.getElementById("libraryVideoModal")
    );

    if (libraryVideoBtn) {
        libraryVideoBtn.addEventListener("click", () => {
            libraryVideoModal.show();
            loadMediaLibrary("video", "videoLibraryContent");
        });
    }

    // 3. From URL
    const urlVideoBtn = document.getElementById("urlVideoBtn");
    const urlVideoModal = new bootstrap.Modal(
        document.getElementById("urlVideoModal")
    );
    const submitVideoUrl = document.getElementById("submitVideoUrl");
    const videoUrlInput = document.getElementById("videoUrlInput");

    if (urlVideoBtn) {
        urlVideoBtn.addEventListener("click", () => {
            urlVideoModal.show();
        });
    }

    if (submitVideoUrl) {
        submitVideoUrl.addEventListener("click", async () => {
            const url = videoUrlInput.value.trim();
            if (!url) return;

            // Validate URL
            if (!isValidVideoUrl(url)) {
                alert("Invalid video URL. Please enter a valid video URL.");
                return;
            }

            displayVideoPreview(url);
            document.getElementById("videoSource").value = "url";
            document.getElementById("videoUrl").value = url;
            urlVideoModal.hide();
            videoUrlInput.value = "";
        });
    }

    // ==================== HELPER FUNCTIONS ====================

    function displayBannerPreview(url) {
        const container = document.querySelector(".banner-preview-container");
        const placeholder = document.getElementById("bannerPlaceholder");

        if (placeholder) {
            placeholder.remove();
        }

        let img = document.getElementById("bannerPreview");
        if (!img) {
            img = document.createElement("img");
            img.id = "bannerPreview";
            img.style.maxWidth = "100%";
            img.style.maxHeight = "100%";
            img.style.objectFit = "contain";
            container.appendChild(img);
        }

        img.src = url;
        img.alt = "Banner Preview";
    }

    function displayVideoPreview(url) {
        const container = document.querySelector(".video-preview-container");
        const placeholder = document.getElementById("videoPlaceholder");

        if (placeholder) {
            placeholder.remove();
        }

        let video = document.getElementById("videoPreview");
        if (!video) {
            video = document.createElement("video");
            video.id = "videoPreview";
            video.controls = true;
            video.style.maxWidth = "100%";
            video.style.maxHeight = "100%";
            container.appendChild(video);
        }

        video.src = url;
    }

    function isValidImageUrl(url) {
        const imageExtensions = [
            ".jpg",
            ".jpeg",
            ".png",
            ".gif",
            ".bmp",
            ".webp",
            ".svg",
        ];
        const urlLower = url.toLowerCase();
        return (
            imageExtensions.some((ext) => urlLower.includes(ext)) ||
            url.startsWith("data:image/")
        );
    }

    function isValidVideoUrl(url) {
        const videoExtensions = [".mp4", ".webm", ".ogg", ".mov", ".avi"];
        const urlLower = url.toLowerCase();
        return (
            videoExtensions.some((ext) => urlLower.includes(ext)) ||
            url.startsWith("data:video/")
        );
    }

    async function loadMediaLibrary(type, containerId) {
        const container = document.getElementById(containerId);

        try {
            // استدعاء API لجلب ملفات المستخدم
            const response = await fetch(`/api/media-library?type=${type}`);
            const data = await response.json();

            if (data.success && data.files.length > 0) {
                let html = "";
                data.files.forEach((file) => {
                    html += `
                        <div class="col-md-3 mb-3">
                            <div class="card media-item" style="cursor: pointer;" data-url="${
                                file.url
                            }" data-type="${type}">
                                ${
                                    type === "image"
                                        ? `<img src="${file.url}" class="card-img-top" alt="${file.name}" style="height: 150px; object-fit: cover;">`
                                        : `<video src="${file.url}" class="card-img-top" style="height: 150px; object-fit: cover;"></video>`
                                }
                                <div class="card-body p-2">
                                    <small class="text-truncate d-block">${
                                        file.name
                                    }</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;

                // Add click handlers
                container.querySelectorAll(".media-item").forEach((item) => {
                    item.addEventListener("click", () => {
                        const url = item.dataset.url;
                        const mediaType = item.dataset.type;

                        if (mediaType === "image") {
                            displayBannerPreview(url);
                            document.getElementById("bannerSource").value =
                                "library";
                            document.getElementById("bannerUrl").value = url;
                            bootstrap.Modal.getInstance(
                                document.getElementById("libraryBannerModal")
                            ).hide();
                        } else {
                            displayVideoPreview(url);
                            document.getElementById("videoSource").value =
                                "library";
                            document.getElementById("videoUrl").value = url;
                            bootstrap.Modal.getInstance(
                                document.getElementById("libraryVideoModal")
                            ).hide();
                        }
                    });
                });
            } else {
                container.innerHTML =
                    '<div class="col-12 text-center p-5"><p class="text-muted">No media files found in your library.</p></div>';
            }
        } catch (error) {
            container.innerHTML =
                '<div class="col-12 text-center p-5"><p class="text-danger">Error loading media library: ' +
                error.message +
                "</p></div>";
        }
    }
});
