package dev.akat.smartmeter.ui.accountInfo

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import dev.akat.smartmeter.R
import dev.akat.smartmeter.model.ServiceInfo
import kotlinx.android.synthetic.main.item_service_list.view.*

class ServiceListAdapter(private val eventListener: EventListener) :
    ListAdapter<ServiceInfo, ServiceListAdapter.ViewHolder>(
        object : DiffUtil.ItemCallback<ServiceInfo>() {
            override fun areItemsTheSame(oldItem: ServiceInfo, newItem: ServiceInfo): Boolean {
                return oldItem.service.id == newItem.service.id
            }

            override fun areContentsTheSame(oldItem: ServiceInfo, newItem: ServiceInfo): Boolean {
                return oldItem == newItem
            }
        }
    ) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int) = ViewHolder(
        LayoutInflater.from(parent.context).inflate(
            R.layout.item_service_list, parent, false
        ), eventListener
    )

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    interface EventListener {
        fun onDeviceClick(serviceInfo: ServiceInfo)
    }

    class ViewHolder(itemView: View, private val eventListener: EventListener) :
        RecyclerView.ViewHolder(itemView) {
        fun bind(item: ServiceInfo) = with(itemView) {
            // name
            service_name_tv.text = item.service.name

            // calculation name
            service_type_value_tv.text = item.calculation.name

            // volume
            service_volume_value_tv.text =
                resources.getString(R.string.volume_format, item.volume.toString(), item.unit.name)

            // coefficient
            service_coefficient_value_tv.text = item.calculation.coefficient.toString()

            // price
            service_tariff_value_tv.text =
                resources.getString(R.string.sum_format, item.tariff.price.toString())

            // sum
            service_summa_value_tv.text =
                resources.getString(R.string.sum_format, item.summa.toString())

            // devices button
            if (!item.devices.isNullOrEmpty()) {
                service_devices_btn.visibility = View.VISIBLE
                service_devices_btn.text =
                    resources.getString(R.string.devices_format, item.devices.size)
            } else service_devices_btn.visibility = View.GONE

            service_devices_btn.setOnClickListener {
                eventListener.onDeviceClick(item)
            }
        }
    }
}