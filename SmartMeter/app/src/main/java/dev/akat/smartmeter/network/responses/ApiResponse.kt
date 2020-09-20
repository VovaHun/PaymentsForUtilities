package dev.akat.smartmeter.network.responses

import com.google.gson.annotations.SerializedName

data class ApiResponse(
    @field:SerializedName("ok")
    val ok: Boolean,
    @field:SerializedName("error_code")
    val errorCode: Int,
    @field:SerializedName("error")
    val error: String
)