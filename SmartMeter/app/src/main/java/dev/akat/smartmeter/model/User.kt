package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class User(
    @field:SerializedName("login")
    val login: String,
    @field:SerializedName("password")
    val password: String,
    @field:SerializedName("name")
    val name: String,
    @field:SerializedName("gender")
    val gender: Int,
    @field:SerializedName("email")
    val email: String,
    @field:SerializedName("email_notifications")
    val emailNotification: Int,
    @field:SerializedName("phone")
    val phone: String,
    @field:SerializedName("phone_notifications")
    val phoneNotification: Int,
    @field:SerializedName("comment")
    val comment: String,
    @field:SerializedName("consent_on_personal_data")
    val consent: Int,
    @field:SerializedName("id")
    val id: Long = -1L,
    @field:SerializedName("last_name")
    val lastName: String = "",
    @field:SerializedName("first_name")
    val firstName: String = "",
    @field:SerializedName("middle_name")
    val middleName: String = ""
)