package dev.akat.smartmeter.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.android.parcel.Parcelize
import java.util.*

@Parcelize
data class Device(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("name")
    val name: String,
    @field:SerializedName("next_date_check")
    val nextDateCheck: Date,
    @field:SerializedName("previous_indications")
    val previousIndications: Double,
    @field:SerializedName("current_indications")
    val currentIndications: Double,
    @field:SerializedName("indications")
    val indications: Double
) : Parcelable