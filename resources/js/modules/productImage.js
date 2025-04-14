import axios from 'axios';

export function uploadProductImage(formData, url) {
    return axios.post(url, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    });
}