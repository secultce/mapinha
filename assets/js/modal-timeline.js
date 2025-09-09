import {
    trans,
    THE_RESOURCE_WAS_CREATED,
    THE_RESOURCE_WAS_UPDATED,
    THE_RESOURCE_WAS_DELETED,
    INVITE_WAS_ACCEPTED,
    INVITE_WAS_SENT,
    TYPE,
    IDENTIFICATION_NUMBER,
    NAME,
    DESCRIPTION,
    AGENTS,
    OWNER,
    CREATED_BY,
    SOCIAL_MEDIA,
    CREATED_AT,
    DELETED,
    UPDATED_AT,
    EXTRA_FIELDS,
    STATE,
    EMAIL,
    CITY_ID,
    CITY_CODE,
    REGION,
    PHONE,
    TERM_FORM,
    FORM,
    TERM_VERSION,
    TERM_STATUS,
} from "../translator.js";


const MODAL_BODY = document.getElementById('modal-timeline-table-body');
const EXTRA_INFO = document.getElementById('modal-timeline-extra-info');
const DATA = document.getElementById('modal-timeline-data');
const TIMELINE_TABLE = document.getElementById('modal-timeline-table');

const FIELDS = {
    authorName: document.getElementById('author-name'),
    authorEmail: document.getElementById('author-email'),
    device: document.getElementById('device'),
    platform: document.getElementById('platform'),
    datetime: document.getElementById('datetime'),
    title: document.getElementById('title'),
    alertAuthorName: document.getElementById('alert-author-name')
};

const FIELD_TRANSLATIONS = {
    "id": trans(IDENTIFICATION_NUMBER),
    "type": trans(TYPE),
    "name": trans(NAME),
    "description": trans(DESCRIPTION),
    "agents": trans(AGENTS),
    "owner": trans(OWNER),
    "createdBy": trans(CREATED_BY),
    "socialNetworks": trans(SOCIAL_MEDIA),
    "createdAt": trans(CREATED_AT),
    "deletedAt": trans(DELETED),
    "updatedAt": trans(UPDATED_AT),
    "extraFields": trans(EXTRA_FIELDS),
};

const EXTRA_FIELD_TRANSLATIONS = {
    "cityId": trans(CITY_ID),
    "cityCode": trans(CITY_CODE),
    "region": trans(REGION),
    "email": trans(EMAIL),
    "phone": trans(PHONE),
    "state": trans(STATE),
    "termo_versao": trans(TERM_FORM),
    "form": trans(FORM),
    "term_version": trans(TERM_VERSION),
    "term_status": trans(TERM_STATUS),
};

function openModal(item) {
    const { from, to, author, data } = item;

    MODAL_BODY.innerHTML = '';

    if (author == null) {
        EXTRA_INFO.style.display = 'none';
    }

    if (author) {
        populateExtraInfo(item);
        EXTRA_INFO.style.display = 'block';
    }

    if (data) {
        DATA.innerHTML = '';

        const ul = document.createElement('ul');

        Object.entries(data).forEach(([key, value]) => {
            const li = document.createElement('li');
            li.innerHTML = `<strong>${translateFieldKey(key)}:</strong> ${formatValue(value)}`;
            ul.appendChild(li);
        });

        DATA.append(ul);
    }

    if (from === undefined && to === undefined) {
        TIMELINE_TABLE.style.display = 'none';
    }

    Object.keys(to ?? {}).forEach(field => {
        const row = createRow(field, from[field], to[field]);
        MODAL_BODY.appendChild(row);
    });
}

function createRow(field, fromValue, toValue) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${FIELD_TRANSLATIONS[field] || field}</td>
        <td>${formatValue(fromValue)}</td>
        <td>${formatValue(toValue)}</td>
    `;
    return row;
}

function formatValue(value) {
    if (value == null || (Array.isArray(value) && value.length === 0)) {
        return 'N/A';
    }

    if (typeof value === 'object' && value.id && value.name) {
        return `${value.name} <br> ID: ${value.id}`;
    }

    if (typeof value === 'object') {
        return formatExtraFields(value);
    }

    return String(value);
}

function formatExtraFields(obj) {
    return Object.entries(obj)
        .map(([key, value]) => {
            const translatedKey = translateFieldKey(key);
            return `<strong>${translatedKey}:</strong> ${formatValue(value)}`;
        })
        .join('<br>');
}

function translateFieldKey(key) {
    return EXTRA_FIELD_TRANSLATIONS[key] || FIELD_TRANSLATIONS[key] || key;
}

function formatDateTime(dateTimeObj) {
    const dateStr = dateTimeObj.date;
    const date = new Date(dateStr);
    return date.toLocaleString();
}

function populateExtraInfo(item) {
    clearFields();

    const { author, datetime, device, platform, title } = item;
    const name = author.socialName ?? `${author.firstname} ${author.lastname}`;

    const translations = {
        "The resource was created": trans(THE_RESOURCE_WAS_CREATED),
        "The resource was updated": trans(THE_RESOURCE_WAS_UPDATED),
        "The resource was deleted": trans(THE_RESOURCE_WAS_DELETED),
        "Invite was accepted": trans(INVITE_WAS_ACCEPTED),
        "Invite was sent": trans(INVITE_WAS_SENT),
    };

    FIELDS.authorName.textContent = name;
    FIELDS.authorEmail.textContent = author.email;
    FIELDS.device.textContent = device;
    FIELDS.platform.textContent = platform;
    FIELDS.datetime.textContent = formatDateTime(datetime);
    FIELDS.title.textContent = translations[title] || title;
    FIELDS.alertAuthorName.textContent = name;
}

function clearFields() {
    Object.values(FIELDS).forEach(field => {
        field.textContent = '';
    });
}

window.openModal = openModal;
